<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Models\User;
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
        return $request->validated();
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
