<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class MahasiswaTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    public function headings(): array
    {
        return ['Nama Panjang', 'Email UGM', 'NIM', 'Angkatan', 'Prodi', 'Jabatan'];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        return new Collection([
            ['ContohNama', 'contohemail@mail.ugm.ac.id', '21/21313/SV/31311', '24', 'TRPL', 'FE (FE, BE, UI/UX, PM)']
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // Row 1
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '4F81BD'], // blue background
                ],
            ],
        ];
    }
}
