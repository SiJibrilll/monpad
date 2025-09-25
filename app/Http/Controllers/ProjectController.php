<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

use App\Http\Resources\ProjectResource;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('owner.dosen_data')->get();


        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        // $project = Project::create($request->validated());
        $project = $request->user()->projects()->create($request->validated());
        $project->load('owner');

        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with('owner.dosen_data')->find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return new ProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $id)
    {
        $project = $request->user()->projects()->find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->update($request->validated());
        $project->load('owner');

        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $project = $request->user()->projects()->find($id);
        if (!$project) {
            return response()->json(['message' => 'Project Not found'], 404);
        }
        $project->delete();
        return response()->noContent();
    }
}
