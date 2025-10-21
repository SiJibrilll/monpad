<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'id',
        'grade',
        'week_id',
        'grade_type_id',
    ];
}
