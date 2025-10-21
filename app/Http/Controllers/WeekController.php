<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeekResource;
use App\Models\Week;
use Illuminate\Http\Request;

class WeekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weeks = Week::with(['grader', 'grades.gradeType', 'weekType'])->get();

        return WeekResource::collection($weeks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Week $week)
    {
        $week->load(['grader', 'grades.gradeType', 'weekType']);
        return new WeekResource($week);
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
