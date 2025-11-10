<?php

namespace App\Models;

use App\Services\GradeCalculator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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

    function memberGrade() {
        $user = $this->user;
        $user->loadMissing(['groups', 'memberShip.qualifications']);
        $membership = $user->memberShip()->where('group_id', $this->project->group->id)->get();
        $membership->loadMissing('qualifications.grades.gradeType');
       
        $avg = $membership->flatMap->qualifications->flatMap->grades->groupBy('personal_grade_type_id')->map(fn($grades) => $grades->avg('grade'));
        
        $result = $this->mapGradesWithTypeNames($avg);

        return $result;
    }

    function finalGrade() {
        return ($this->projectGrade() * 0.5) + ($this->personalGrade($this->user_id) * 0.3) + ($this->memberGrade()->avg() * 0.2);
    }

    
    function mapGradesWithTypeNames(Collection $member_grade): Collection
    {
        // Get all grade type names once
        $gradeTypes = DB::table('personal_grade_types')
            ->whereIn('id', $member_grade->keys())
            ->pluck('name', 'id'); // [id => name]

        // Map the collection with names
        // return $member_grade->map(function ($grade, $id) use ($gradeTypes) {
        //     return [
        //         'id' => $id,
        //         'name' => $gradeTypes[$id] ?? 'Unknown',
        //         'grade' => $grade,
        //     ];
        // })->values(); // reset numeric keys

        //version where keys are directly mapped
        return $member_grade->mapWithKeys(fn($grade, $id) => [
            $gradeTypes[$id] ?? 'Unknown' => $grade,
        ]);
    }

}
