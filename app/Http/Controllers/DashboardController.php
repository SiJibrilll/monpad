<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function asisten() {
        $mahasiswaCount = User::mahasiswa()->count();
        $progress = "aribtrary value here";

        return response()->json([
            'jumlah_mahasiswa' => $mahasiswaCount,
            'progres' => $progress
        ], 200);
    }

    function dosen() {
        $mahasiswaCount = User::mahasiswa()->count();
        $asistenCount = User::asisten()->count();
        $projectCount = Project::count();
        $progress = "aribtrary value here";

        return response()->json([
            'jumlah_mahasiswa' => $mahasiswaCount,
            'jumlah_asisten' => $asistenCount,
            'jumlah_projek' => $projectCount,
            'progres' => $progress
        ]);
    }
}
