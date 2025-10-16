<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeekType extends Model
{
    protected $fillable = [
        'name',
        'percentage'
    ];

    function gradeType() {
        return $this->belongsToMany(GradeType::class, 'week_type_grade_type');
    }
}
