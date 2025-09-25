<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    
    protected $fillable = [
        'nama_projek',
        'semester',
        'deskripsi',
        'tahun_ajaran',
        'user_id'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
