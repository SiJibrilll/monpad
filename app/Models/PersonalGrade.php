<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalGrade extends Model
{
    protected $fillable = [
        'personal_grade_type_id',
        'grade'
    ];
}
