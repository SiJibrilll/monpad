<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeTypeResource;
use App\Models\GradeType;
use Illuminate\Http\Request;

class GradeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gradeTypes = GradeType::all();

        return GradeTypeResource::collection($gradeTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:grade_types,name'],
            'percentage' => ['required', 'integer']
        ]);

        $weekType = GradeType::create($validated);

        return new GradeTypeResource($weekType);
    }

    /**
     * Display the specified resource.
     */
    public function show(GradeType $gradeType)
    {
        return new GradeTypeResource($gradeType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GradeType $gradeType)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'unique:grade_types,name,' . $gradeType->id],
            'percentage' => ['sometimes', 'integer']
        ]);

        $gradeType->update($validated);
        $gradeType->refresh();

        return new GradeTypeResource($gradeType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GradeType $gradeType)
    {
        $gradeType->delete();

        return response()->noContent();
    }
}
