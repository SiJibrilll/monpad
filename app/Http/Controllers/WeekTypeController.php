<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeekTypeResource;
use App\Models\WeekType;
use Illuminate\Http\Request;

class WeekTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weekTypes = WeekType::all();

        return WeekTypeResource::collection($weekTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:week_types,name'],
            'percentage' => ['required', 'integer']
        ]);

        $weekType = WeekType::create($validated);

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
            'percentage' => ['sometimes', 'integer']
        ]);

        $weekType->update($validated);
        $weekType->refresh();

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
