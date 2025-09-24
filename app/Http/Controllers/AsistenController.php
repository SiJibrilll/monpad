<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Asisten;
use App\Http\Resources\AsistenResource;

use App\Http\Requests\StoreAsistenRequest;
use App\Http\Requests\UpdateAsistenRequest;

class AsistenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asisten = User::asisten()->get();

        return AsistenResource::collection($asisten);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAsistenRequest $request)
    {
        $asisten = Asisten::createAsisten($request->validated());

        return new AsistenResource($asisten);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asisten = User::asisten()->find($id);

        if (!$asisten) {
            return response()->json(['message' => 'Asisten Not found'], 404);
        }

        return new AsistenResource($asisten);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAsistenRequest $request, string $id)
    {
        $asisten = Asisten::updateAsisten($request->validated(), $id);

        return new AsistenResource($asisten);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
