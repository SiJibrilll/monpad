<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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

        Route::apiResource('mahasiswa', MahasiswaController::class)->middleware('not_finalized');

        Route::apiResource('dosen', DosenController::class);
        
        Route::apiResource('asisten', AsistenController::class);
        
        Route::apiResource('project', ProjectController::class)->middleware('not_finalized');
        
        Route::apiResource('group', GroupConctroller::class)->middleware('not_finalized');
        
        Route::apiResource('group.members', GroupMemberController::class)->only(['index', 'store', 'destroy'])->middleware('not_finalized');

        Route::apiResource('week-type', WeekTypeController::class);
        
        Route::apiResource('grade-type', GradeTypeController::class);

        Route::apiResource('week.review', GradeNoteController::class);

        //grade finalizations
        Route::get('/finalization', [FinalizationController::class, 'index']);
        Route::post('/finalization/{finalization}', [FinalizationController::class, 'finalize']);    
        
        Route::get('/dashboard/dosen', [DashboardController::class, 'dosen']);
    });

    // -- asisten/dosen features
    Route::middleware(['role:asisten|dosen'])->group(function () {
        Route::apiResource('week', WeekController::class)->middleware('not_finalized');
    });

    // -- asisten features
    Route::middleware(['role:asisten', 'not_finalized'])->group(function () {
        // presence feature
        Route::get('group/{group}/weekly-presence/{weekType}', [PresenceController::class, 'index']);
        Route::put('group/{group}/weekly-presence/{weekType}', [PresenceController::class, 'update']);

        Route::get('/dashboard/asisten', [DashboardController::class, 'asisten']);
    });

    Route::middleware(['role:mahasiswa'])->group(function () {
        Route::post('/group/{group}/members/{member}/qualification', [QualificationController::class, 'store'])->middleware('not_finalized');

        Route::get('/dashboard/mahasiswa', [DashboardController::class, 'mahasiswa']);
    });
    

    // global routes
    Route::get('/profile', [AuthController::class, 'profile']);
});