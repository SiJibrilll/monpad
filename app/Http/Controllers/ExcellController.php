<?php

namespace App\Http\Controllers;

use App\Exports\MahasiswaTemplateExport;
use App\Imports\MahasiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcellController extends Controller
{
    function mahasiswaTemplate() {
        return Excel::download(new MahasiswaTemplateExport, 'templateMahasiswa.xlsx');
    }

    function mahasiswaImport(Request $request) {
        $import = new MahasiswaImport;

        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            return response()->json([
                'imported' => 'partially',
                'failures' => $import->failures(),
            ], 422);
        }

        return response()->json(['imported' => 'success']);
    }
}
