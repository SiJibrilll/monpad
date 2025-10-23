<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeekRequest;
use App\Http\Resources\WeekResource;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weeks = Week::with(['grader', 'grades.gradeType', 'weekType', 'review.writer'])->get();

        return WeekResource::collection($weeks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWeekRequest $request)
    {
        $data = $request->validated();

        $week = Week::create($data);

        foreach ($data['grades'] as $grade) {
            $week->grades()->create($grade);
        }

        $week->load('grader', 'grades.gradeType', 'weekType', 'review.writer');

        return new WeekResource($week);
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
    public function update(StoreWeekRequest $request, Week $week)
    {
        $data = $request->validated();

        $gradesData = collect($data['grades']);
        $week->update($data);

        DB::transaction(function () use ($week, $gradesData) {
            foreach ($gradesData as $grade) {
                $week->grades()->updateOrCreate(
                    ['grade_type_id' => $grade['grade_type_id'], 'week_id' => $week->id],
                    [
                        'grade' => $grade['grade']
                    ]
                );
            }

            
            $validIds = $gradesData->pluck('grade_type_id');
            $week->grades()
                ->whereNotIn('grade_type_id', $validIds)
                ->delete();
        });

        
        $week->refresh();
        $week->load(['grader', 'grades.gradeType', 'weekType']);
        
        return new WeekResource($week);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Week $week)
    {
        $week->delete();

        return response()->noContent();
    }
}
