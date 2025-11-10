<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Dosen extends Model
{
    protected $table = 'dosen_datas';

    protected $fillable = [
        'nidn',
        'user_id',
        'fakultas'
    ];

    static function createDosen($data) {
        

        $userData = Arr::only($data, ['name', 'email', 'password']);
        $dosenData = Arr::only($data, ['nidn', 'fakultas']);

        $user = User::create($userData);
        $user->dosen_data()->create($dosenData);
        $user->assignRole('dosen');
        
        return $user;
    }

    static function updateDosen($data, $id) {
        

        $userData = Arr::only($data, ['name', 'email', 'password']);
        $dosenData = Arr::only($data, ['nidn', 'fakultas']);

        $user = User::findOrFail($id);
        $user->update($userData);
        $user->dosen_data()->update($dosenData);

        return $user;
    }
}
