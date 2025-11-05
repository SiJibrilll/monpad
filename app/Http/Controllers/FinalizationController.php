<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeFinalizationResource;
use App\Models\GradeFinalization;
use App\Models\Project;
use App\Models\User;
use App\Models\Week;
use App\Services\PresenceRuleValidator;
use Illuminate\Http\Request;

class FinalizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = User::mahasiswa()->first();
        // $week = Week::with('weekType.presenceRule')->find(2);
        // $presenceValidator = new PresenceRuleValidator;
        // $project = Project::first();
        // dd($project->finalGrade());
        $finalizations = GradeFinalization::with(['user.mahasiswa_data', 'user.presences', 'project.weeks', 'project.group'])->get();
        return GradeFinalizationResource::collection($finalizations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /*
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
