<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Resources\DosenResource;
use App\Http\Resources\MahasiswaResource;
use App\Http\Resources\AsistenResource;

class UserResourceService
{
    public static function make($user)
    {
        $role = $user->getRoleNames()->first();

        return match ($role) {
            'dosen' => new DosenResource($user->load('dosen_data')),
            'mahasiswa' => new MahasiswaResource($user->load('mahasiswa_data')),
            'asisten' => new AsistenResource($user->load('asisten_data')),
        };
    }
}
