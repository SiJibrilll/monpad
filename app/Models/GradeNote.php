<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeNote extends Model
{
    protected $fillable = [
        'writer_id',
        'week_id',
        'note'
    ];

    function writer() {
        return $this->belongsTo(User::class, 'writer_id');
    }

    function week() {
        return $this->belongsTo(Week::class);
    }
}
