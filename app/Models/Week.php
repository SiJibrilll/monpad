<?php

namespace App\Models;

use App\Services\GradeCalculator;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    protected $fillable = [
        'id',
        'project_id',
        'grader_id',
        'date',
        'notes',
        'week_type_id'
    ];

    function grader() {
        return $this->belongsTo(User::class, 'grader_id');
    }

    function grades() {
        return $this->hasMany(Grade::class);
    }

    function project() {
        return $this->belongsTo(Project::class);
    }

    function weekType() {
        return $this->belongsTo(WeekType::class);
    }

    function totalGrade() {
        $calculator = new GradeCalculator;
        return $calculator->calculateWeekTotal($this);
    }

    function review() {
        return $this->hasOne(GradeNote::class);
    }
}
