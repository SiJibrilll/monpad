<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\GradeNoteController;
use App\Http\Controllers\GradeTypeController;
use App\Http\Controllers\GroupConctroller;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WeekController;
use App\Http\Controllers\WeekTypeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('mahasiswa', MahasiswaController::class);

Route::apiResource('dosen', DosenController::class);

Route::apiResource('asisten', AsistenController::class);

Route::apiResource('project', ProjectController::class);

Route::apiResource('group', GroupConctroller::class);

Route::apiResource('group.members', GroupMemberController::class)->only(['index', 'store', 'destroy']);

Route::apiResource('week-type', WeekTypeController::class);

Route::apiResource('grade-type', GradeTypeController::class);

Route::apiResource('week', WeekController::class);

Route::apiResource('week.review', GradeNoteController::class);

// presence feature
Route::get('group/{group}/weekly-presence/{weekType}', [PresenceController::class, 'index']);
Route::put('group/{group}/weekly-presence/{weekType}', [PresenceController::class, 'update']);