<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonalGradeRequest;
use App\Models\Group;
use App\Models\PersonalGrade;
use App\Models\Qualification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonelGradingController extends Controller
{
    function store(StorePersonalGradeRequest $request, Group $group, User $member) {
        $validated = $request->validated();
        $grader = $request->user();

        // Check if grader or member is NOT in this group
        $graderInGroup = $grader->groups()->where('groups.id', $group->id)->exists();
        $memberInGroup = $member->groups()->where('groups.id', $group->id)->exists();

        if (! $graderInGroup || ! $memberInGroup) {
            return response()->json([
                'message' => 'Either the grader or the member is not part of this group.'
            ], 403);
        }

        
        $groupMember = DB::table('group_members')->where('group_id', $group->id)->where('user_id', $member->id)->firstOrFail();
        
        if (Qualification::where('group_member_id', $groupMember->id)->where('grader_id', $grader->id)->exists()) {
            return response()->json([
                'message' => 'Cannot grade the same member'
            ], 403);
        }

        // catat kualifikasi
        $qualification = Qualification::create([
            'notes' => $validated['notes'],
            'group_member_id' => $groupMember->id,
            'grader_id' => $grader->id
        ]);

        // catat nilai-nilai personal yang akan membangun kualifikasi
        foreach ($validated['grades'] as $grade) {
            $qualification->grades()->create($grade);
        }


        return response()->json([
            'message' => 'Qualification grading stored successfully!'
        ], 200);
    }
}
