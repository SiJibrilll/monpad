<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Http\Resources\MahasiswaResource;


class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::mahasiswa()->get();
        return MahasiswaResource::collection($users);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMahasiswaRequest $request)
    {
        $user = Mahasiswa::createMahasiswa($request->validated());

        return (new MahasiswaResource($user))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::mahasiswa()->findOrFail($id);

        return new MahasiswaResource($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMahasiswaRequest $request, string $id)
    {
        $user = Mahasiswa::updateMahasiswa($request->validated(), $id);

        return new MahasiswaResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Mahasiswa deleted successfully'], 204);
    }
}
