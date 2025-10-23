<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeNoteResource;
use App\Models\GradeNote;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request, Week $week)
    {
        $validated = $request->validate([
            'note' => ['required', 'string']
        ]);

        $validated['writer_id'] = $request->user()->id;

        $review = $week->review()->create($validated);

        $review->load('writer');

        return new GradeNoteResource($review);
    }

    /**
     * Display the specified resource.
     */
    public function show(Week $week, GradeNote $review)
    {
        
        if ($review->week_id !== $week->id) {
            abort(404);
        }

        $review->load('writer');

        return new GradeNoteResource($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Week $week, GradeNote $review)
    {
        if ($review->week_id !== $week->id) {
            abort(404);
        }

        if ($review->writer_id !== $request->user()->id) {
            abort(403, 'You are not authorized to update this note');
        }

        $validated = $request->validate([
            'note' => ['required', 'string']
        ]);

        // $review = $week->review()->create($validated);
        $review->update($validated);

        $review->load('writer');

        return new GradeNoteResource($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Week $week, GradeNote $review)
    {
        if ($review->writer_id !== Auth::id()) {
            abort(403, 'You are not authorized to delete this note.');
        }

        $review->delete();

        return response()->noContent();
    }
}
