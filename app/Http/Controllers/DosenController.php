<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen;
use App\Http\Resources\DosenResource;

use App\Http\Requests\StoreDosenRequest;
use App\Http\Requests\UpdateDosenRequest;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosens = User::dosen()->get();

        return DosenResource::collection($dosens);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDosenRequest $request)
    {
        $dosen = Dosen::createDosen($request->validated());

        return (new DosenResource($dosen))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dosen = User::dosen()->find($id);
        if (!$dosen) {
            return response()->json(['message' => 'Dosen Not found'], 404);
        }

        return new DosenResource($dosen);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDosenRequest $request, string $id)
    {
        $dosen = Dosen::updateDosen($request->validated(), $id);

        return new DosenResource($dosen);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Dosen Not found'], 404);
        }
        $user->delete();
        return response()->noContent();
    }
}
