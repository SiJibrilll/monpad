<?php

namespace App\Models;

use App\Services\GradeCalculator;
use Illuminate\Database\Eloquent\Model;

class GradeFinalization extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'confirmed'
    ];

    function user() {
        return $this->belongsTo(User::class);
    }

    function project() {
        return $this->belongsTo(Project::class);
    }

    function projectGrade() {
        $weeks = $this->project->weeks;
        $gradeCalculator = new GradeCalculator;
        return $gradeCalculator->calculateProjectGrade($weeks);
    }

    function personalGrade($userId) {
        $specialWeeksCount = WeekType::has('presenceRule')->count();
        $personalWeeks = $this->project->weeks()->has('weekType.presenceRule')->with('weekType.presenceRule')->get();
        $presenceCount = User::with('presences')->find($userId)->presences()->count();
        
        $gradeSum = 0;
        foreach ($personalWeeks as $personal) {
            if ($presenceCount < $personal->weekType->presenceRule->minimum) {
                continue;
            }

            $gradeSum += $personal->totalGrade();
        }
        return $gradeSum / $specialWeeksCount;

    }
}
