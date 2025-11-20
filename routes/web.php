<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExcellController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/auth/google', [AuthController::class, 'googleSignIn']);

Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);


Route::middleware(['auth:sanctum'])->group(function () {
    // -- dosen features
    Route::middleware(['role:dosen'])->group(function () {
        //excell routes
        Route::get('/excel/mahasiswa/template', [ExcellController::class, 'mahasiswaTemplate']);
    });
});
