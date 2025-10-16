<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeekTypeResource;
use App\Models\GradeType;
use App\Models\WeekType;
use Illuminate\Http\Request;

class WeekTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weekTypes = WeekType::with('gradeType')->get();

        return WeekTypeResource::collection($weekTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:week_types,name'],
            'percentage' => ['required', 'integer'],
            'grade_types' => ['required', 'array'],
            'grade_types.*' => ['integer', 'exists:grade_types,id']
        ]);

        $weekType = WeekType::create($validated);

        $weekType->gradeType()->sync($validated['grade_types']);

        $weekType->load('gradeType');

        return new WeekTypeResource($weekType);
    }

    /**
     * Display the specified resource.
     */
    public function show(WeekType $weekType)
    {
        return new WeekTypeResource($weekType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeekType $weekType)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'unique:week_types,name,' . $weekType->id],
            'percentage' => ['sometimes', 'integer'],
            'grade_types' => ['sometimes', 'array'],
            'grade_types.*' => ['sometimes', 'exists:grade_types,id']
        ]);

        $weekType->update($validated);
        $weekType->gradeType()->sync($validated['grade_types']);
        $weekType->refresh();
        $weekType->load('gradeType');

        return new WeekTypeResource($weekType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeekType $weekType)
    {
        $weekType->delete();

        return response()->noContent();
    }
}
