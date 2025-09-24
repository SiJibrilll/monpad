<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa_datas';

    protected $fillable = [
        'nim',
        'prodi',
        'angkatan',
        'user_id',
        'jabatan'
    ];

    static function createMahasiswa($data) {
        

        $userData = Arr::only($data, ['name', 'email', 'password']);
        $mahasiswaData = Arr::only($data, ['nim', 'prodi', 'angkatan', 'jabatan']);

        $user = User::create($userData);
        $user->mahasiswa_data()->create($mahasiswaData);
        return $user;
    }

    static function updateMahasiswa($data, $id) {
        

        $userData = Arr::only($data, ['name', 'email', 'password']);
        $mahasiswaData = Arr::only($data, ['nim', 'prodi', 'angkatan', 'jabatan']);

        $user = User::findOrFail($id);
        $user->update($userData);
        $user->mahasiswa_data()->update($mahasiswaData);

        return $user;
    }
}
