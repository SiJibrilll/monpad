<?php

namespace App\Models;

use App\Models\Concerns\Finalizable;
use App\Services\GradeCalculator;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    use Finalizable;
    
    protected $fillable = [
        'nama_projek',
        'semester',
        'deskripsi',
        'tahun_ajaran',
        'user_id',
        'asisten_id'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function asisten() {
        return $this->belongsTo(User::class, 'asisten_id');
    }

    public function group() {
        return $this->hasOne(Group::class, 'project_id');
    }

    function weeks() {
        return $this->hasMany(Week::class);
    }

    function currentPeriod() {
        $weekTypeCount = WeekType::count();
        $weeks = $this->weeks()->count();

        return "$weeks / $weekTypeCount";
    }

    function projectGrade() {
        $weeks = $this->weeks;
        $gradeCalculator = new GradeCalculator;
        return $gradeCalculator->calculateProjectGrade($weeks);
    }


    function finalizations() {
        return $this->hasMany(GradeFinalization::class);
    }
}
