<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExcellController;
use App\Http\Controllers\FinalizationController;
use App\Http\Controllers\GradeNoteController;
use App\Http\Controllers\GradeTypeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\WeekController;
use App\Http\Controllers\WeekTypeController;

// auth
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {

    // global
    Route::get('/profile', [AuthController::class, 'profile']);


    // =====================================================
    // DOSEN-ONLY ROUTES
    // =====================================================
    Route::middleware(['role:dosen'])->group(function () {

        Route::apiResource('mahasiswa', MahasiswaController::class)
            ->middleware('not_finalized');

        Route::apiResource('dosen', DosenController::class);
        Route::apiResource('asisten', AsistenController::class);

        Route::apiResource('project', ProjectController::class)
            ->middleware('not_finalized');

        Route::apiResource('group', GroupController::class)
            ->middleware('not_finalized');

        Route::apiResource('group.members', GroupMemberController::class)
            ->only(['index', 'store', 'destroy'])
            ->middleware('not_finalized');

        Route::apiResource('week-type', WeekTypeController::class);
        Route::apiResource('grade-type', GradeTypeController::class);
        Route::apiResource('week.review', GradeNoteController::class);

        Route::get('/finalization', [FinalizationController::class, 'index']);
        Route::post('/finalization/{finalization}', [FinalizationController::class, 'finalize']);

        Route::get('/dashboard/dosen', [DashboardController::class, 'dosen']);

        Route::post('/excel/mahasiswa/import', [ExcellController::class, 'mahasiswaImport']);
    });


    // =====================================================
    // SHARED ASISTEN + DOSEN (NO CONFLICTS) WITH READONLY FILTERING
    // =====================================================
    Route::middleware(['role:asisten|dosen'])->group(function () {
        Route::apiResource('week', WeekController::class)->middleware('not_finalized');

        // READ ONLY: mahasiswa
        Route::get('mahasiswa',          [MahasiswaController::class, 'index']);
        Route::get('mahasiswa/{id}',     [MahasiswaController::class, 'show']);

        // READ ONLY: project
        Route::get('project',            [ProjectController::class, 'index']);
        Route::get('project/{id}',       [ProjectController::class, 'show']);

        // READ ONLY: group
        Route::get('group',              [GroupController::class, 'index']);
        Route::get('group/{id}',         [GroupController::class, 'show']);

        // READ ONLY: group.members
        Route::get('group/{group}/members', [GroupMemberController::class, 'index']);
        Route::get('group/{group}/members/{member}', [GroupMemberController::class, 'show']);

        // READ ONLY: week-type
        Route::get('week-type',          [WeekTypeController::class, 'index']);
        Route::get('week-type/{id}',     [WeekTypeController::class, 'show']);

        // READ ONLY: grade-type
        Route::get('grade-type',         [GradeTypeController::class, 'index']);
        Route::get('grade-type/{id}',    [GradeTypeController::class, 'show']);

        // READ ONLY: week.review
        Route::get('week/{week}/review',                 [GradeNoteController::class, 'index']);
        Route::get('week/{week}/review/{review}',        [GradeNoteController::class, 'show']);
    });


    // =====================================================
    // ASISTEN ROUTES 
    // =====================================================
    Route::middleware(['role:asisten', 'not_finalized'])->group(function () {

        // presence
        Route::get('group/{group}/weekly-presence/{weekType}', [PresenceController::class, 'index']);
        Route::put('group/{group}/weekly-presence/{weekType}', [PresenceController::class, 'update']);

        Route::apiResource('mahasiswa', MahasiswaController::class)->only(['index', 'show']);

        Route::apiResource('project', ProjectController::class)->only(['index', 'show']);
        
        Route::apiResource('group', GroupConctroller::class)->only(['index', 'show']);
        
        Route::apiResource('group.members', GroupMemberController::class)->only(['index', 'store', 'destroy'])->only(['index', 'show']);

        Route::apiResource('week-type', WeekTypeController::class)->only(['index', 'show']);
        
        Route::apiResource('grade-type', GradeTypeController::class)->only(['index', 'show']);

        Route::apiResource('week.review', GradeNoteController::class)->only(['index', 'show']);

        Route::get('/dashboard/asisten', [DashboardController::class, 'asisten']);
    });




    // =====================================================
    // MAHASISWA ROUTES
    // =====================================================
    Route::middleware(['role:mahasiswa'])->group(function () {

        Route::post('/group/{group}/members/{member}/qualification',
            [QualificationController::class, 'store']
        )->middleware('not_finalized');

        Route::get('/dashboard/mahasiswa', [DashboardController::class, 'mahasiswa']);
    });

});

