<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\ProjectController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('mahasiswa', MahasiswaController::class);

Route::apiResource('dosen', DosenController::class);

Route::apiResource('asisten', AsistenController::class);

Route::apiResource('project', ProjectController::class);