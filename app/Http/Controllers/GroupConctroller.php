<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class GroupConctroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $group = Group::with('members')->get();

        return GroupResource::collection($group);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'project_id' => ['required', 'exists:projects,id']
        ]);

        $group = Group::create($validated);

        return new GroupResource($group)->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        $group->load('members');

        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string'],
            'project_id' => ['sometimes', 'exists:projects,id']
        ]);

        $group->update($validated);
        $group->refresh();

        $group->load(['members']);

        return new GroupResource($group);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json(['message' => 'Group Not found'], 404);
        }
        $group->delete();
        return response()->noContent();
    }
}
