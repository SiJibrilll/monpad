<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeType extends Model
{
    protected $fillable = [
        'name',
        'percentage'
    ];

        function weekType() {
        return $this->belongsToMany(WeekType::class, 'week_type_grade_type');
    }
}
