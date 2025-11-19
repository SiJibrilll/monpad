<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Models\Project;
use App\Models\User;
use App\Services\GradeCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function index() {
        $role = Auth::user()->getRoleNames()->first();
        return match ($role) {
            'asisten' => $this->asisten(),
            'dosen' => $this->dosen(),
            'mahasiswa' => $this->mahasiswa()
        };
    }


    function asisten() {
        $gradeCalculator = new GradeCalculator;
        $mahasiswaCount = User::mahasiswa()->count();
        $projects = Project::all();

        return response()->json([
            'jumlah_mahasiswa' => $mahasiswaCount,
            'rata_rata' => $gradeCalculator->globalAverage($projects)
        ], 200);
    }

    function dosen() {
        $gradeCalculator = new GradeCalculator;
        $projects = Project::all();
        $mahasiswaCount = User::mahasiswa()->count();
        $asistenCount = User::asisten()->count();
        $rataRata = $gradeCalculator->globalAverage($projects);

        return response()->json([
            'jumlah_mahasiswa' => $mahasiswaCount,
            'jumlah_asisten' => $asistenCount,
            'jumlah_projek' => $projects->count(),
            'rata_rata' => $rataRata
        ]);
    }

    function mahasiswa() {
        $groups = Auth::user()->groups()->with(['project.weeks.weekType', 'project.weeks.grades.gradeType', 'project.finalizations','members', 'project.weeks'])->get();

        return response()->json([
            'groups' => GroupResource::collection($groups)
        ], 200);

    }
}
