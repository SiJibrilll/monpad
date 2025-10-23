<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeNoteResource;
use App\Models\GradeNote;
use Illuminate\Http\Request;

class GradeNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gradeNotes = GradeNote::with('writer')->get();

        return GradeNoteResource::collection($gradeNotes);
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
