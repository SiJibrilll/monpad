<?php

namespace App\Http\Controllers;

use App\Exports\MahasiswaTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcellController extends Controller
{
    function mahasiswaTemplate() {
        return Excel::download(new MahasiswaTemplateExport, 'templateMahasiswa.xlsx');
    }
}
