<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinalizationController;
use App\Http\Controllers\GradeNoteController;
use App\Http\Controllers\GradeTypeController;
use App\Http\Controllers\GroupConctroller;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\WeekController;
use App\Http\Controllers\WeekTypeController;

// auth routes
Route::post('login', [AuthController::class, 'login']);

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    
    // -- dosen features
    Route::middleware(['role:dosen'])->group(function () {

        Route::apiResource('mahasiswa', MahasiswaController::class);

        Route::apiResource('dosen', DosenController::class);
        
        Route::apiResource('asisten', AsistenController::class);
        
        Route::apiResource('project', ProjectController::class);
        
        Route::apiResource('group', GroupConctroller::class);
        
        Route::apiResource('group.members', GroupMemberController::class)->only(['index', 'store', 'destroy']);

        Route::apiResource('week-type', WeekTypeController::class);
        
        Route::apiResource('grade-type', GradeTypeController::class);

        Route::apiResource('week.review', GradeNoteController::class);

        
    });

    // -- asisten/dosen features
    Route::middleware(['role:asisten|dosen'])->group(function () {
        Route::apiResource('week', WeekController::class);
    });

    // -- asisten features
    Route::middleware(['role:asisten'])->group(function () {
        // presence feature
        Route::get('group/{group}/weekly-presence/{weekType}', [PresenceController::class, 'index']);
        Route::put('group/{group}/weekly-presence/{weekType}', [PresenceController::class, 'update']);
    });

    Route::middleware(['role:mahasiswa'])->group(function () {

         Route::post('/group/{group}/members/{member}/qualification', [QualificationController::class, 'store']);
    });
    
    
});

Route::apiResource('/finalization', FinalizationController::class);