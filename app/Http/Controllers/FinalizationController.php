<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeFinalizationResource;
use App\Models\GradeFinalization;
use App\Models\PersonalGradeType;
use App\Models\Project;
use App\Models\User;
use App\Models\Week;
use App\Models\WeekType;
use App\Services\PresenceRuleValidator;
use Illuminate\Http\Request;

class FinalizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $finalizations = GradeFinalization::with(['user.mahasiswa_data', 'user.presences', 'user.groups', 'user.memberShip.qualifications.grades', 'project.weeks', 'project.group'])->get();
        return GradeFinalizationResource::collection($finalizations);
    }

    function finalize(GradeFinalization $finalization)
    {
        $finalization->load('project.weeks');
        $weekCount = WeekType::count();

        if ($finalization->project->weeks()->count() != $weekCount) {
            return response()->json([
                'message' => "Project grades are incomplete"
            ], 403);
        }
        $finalization->confirmed = true;
        $finalization->update();

        return response()->json([
            'message' => 'Grade Finalized Sucessfully'
        ], 200);
    }
}
