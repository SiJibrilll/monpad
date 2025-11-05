<?php

namespace App\Models;

use App\Services\GradeCalculator;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    
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

    // function finalGrade() {
    //     $weeks = $this->weeks()->with('grades.gradeType')->get();
    //     $gradeCalulator = new GradeCalculator;

    //     $workers = $this->group->members()->with(['presences', 'mahasiswa_data'])->get();
    //     $weekGrades = $gradeCalulator->getWeeklyGrades($weeks);
    //     //okay i have a list of weeks and its grades now
    //     // and i also have a list of mahasiswa's responsible for this project and their presences
    //     // no i just need to tie this all together

    //     // return $gradeCalulator->getWeeklyGrades($weeks);
    //     return $weekGrades;
    // }
}
