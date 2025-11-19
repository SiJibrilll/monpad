<?php

namespace App\Http\Controllers;

use App\Http\Resources\MahasiswaResource;
use App\Models\Group;
use App\Models\User;
use App\Rules\isMahasiswa;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    function index(Group $group) {
        $members = $group->members()->with('mahasiswa_data')->get();
        
        return MahasiswaResource::collection($members);
    }

    function store(Group $group, Request $request) {
        $validated = $request->validate([
            'user_id' => ['required', 'array'],
            'user_id.*' => ['integer', 'exists:users,id', new isMahasiswa]
        ]);

        $group->members()->sync($validated['user_id']);
        $group->refresh();

        //create finalization record
        foreach ($validated['user_id'] as $userId) {
            $group->project->finalizations()->create([
                'user_id' => $userId,
            ]);
        }


        $members = $group->members()->with('mahasiswa_data')->get();

        return MahasiswaResource::collection($members);
        
    }

    function destroy(Group $group, User $member) {
        if (! $group->members()->where('users.id', $member->id)->exists()) {
            return response()->json([
                'error' => 'This user is not a member of the group.'
            ], 404);
        }

        $group->members()->detach($member->id);
        $group->project->finalizations()->find($member->id)->first()->delete();

        return response()->json([
            'message' => 'Member removed successfully.'
        ]);
    }
}
