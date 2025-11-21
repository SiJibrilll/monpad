<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class MahasiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
         // jaga jaga dummy user lupa dihapus
        if (
            strtolower(trim($row['nama_panjang'])) === 'contohnama' ||
            strtolower(trim($row['email_ugm'])) === 'contohemail@mail.ugm.ac.id'
        ) {
            return null; // skip this row entirely
        }

        $password = Str::random(10);

        return Mahasiswa::createMahasiswa([
            'name' => $row['nama_panjang'],
            'email' => $row['email_ugm'],
            'nim' => $row['nim'],
            'angkatan' => $row['angkatan'],
            'prodi' => $row['prodi'],
            'jabatan' => $row['jabatan'],
            'password' => bcrypt($password)
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_panjang'  => 'required|string',
            '*.email_ugm' => 'required|email|unique:users,email',

            '*.nim' => 'required|string|unique:mahasiswa_datas',
            '*.angkatan' => 'required|integer',
            '*.prodi' => 'required|string',
            '*.jabatan' => 'required|in:PM,UI/UX,BE,FE'
        ];
    }

}
