<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Asisten extends Model
{
    protected $table = 'asisten_datas';

    protected $fillable = [
        'tahun_ajaran',
        'user_id'
    ];

    static function createAsisten($data) {
        

        $userData = Arr::only($data, ['name', 'email', 'password']);
        $asistenData = Arr::only($data, ['tahun_ajaran']);

        $user = User::create($userData);
        $user->asisten_data()->create($asistenData);
        return $user;
    }

    static function updateAsisten($data, $id) {
        

        $userData = Arr::only($data, ['name', 'email', 'password']);
        $asistenData = Arr::only($data, ['tahun_ajaran']);

        $user = User::findOrFail($id);
        $user->update($userData);
        $user->asisten_data()->update($asistenData);

        return $user;
    }
}
