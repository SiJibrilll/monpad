<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    function mahasiswa() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
