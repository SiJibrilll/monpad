<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable =[
        'week_type_id',
        'user_id',
        'present'
    ];

    protected $casts = [
        'present' => 'boolean',
    ];


    function mahasiswa() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
