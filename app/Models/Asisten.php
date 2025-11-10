<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Asisten extends Model
{
    protected $table = 'asisten_datas';

    protected $fillable = [
        'tahun_ajaran',
        'user_id',
        'nim'
    ];

    static function createAsisten($data) {
        
        $user = DB::transaction(function () use ($data) {
            $userData = Arr::only($data, ['name', 'email', 'password']);
            $asistenData = Arr::only($data, ['tahun_ajaran', 'nim']);

            $user = User::create($userData);
            $user->asisten_data()->create($asistenData);
            $user->assignRole('asisten');

            return $user;
        });
        
        return $user;
    }

    static function updateAsisten($data, $id) {
        

        $userData = Arr::only($data, ['name', 'email', 'password']);
        $asistenData = Arr::only($data, ['tahun_ajaran', 'nim']);

        $user = User::findOrFail($id);
        $user->update($userData);
        $user->asisten_data()->update($asistenData);

        return $user;
    }
}
