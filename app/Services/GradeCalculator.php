<?php

namespace App\Services;

use App\Models\User;
use App\Models\Week;
use Illuminate\Database\Eloquent\Collection;

class GradeCalculator
{
    public function calculateWeekTotal(Week $week): ?float
    {
        $week->loadMissing('grades.gradeType');

        $grades = $week->grades;

        if ($grades->isEmpty()) {
            return null;
        }

        $weightedSum = 0;
        $totalWeight = 0;

        foreach ($grades as $grade) {
            $gradeValue = $grade->grade ?? 0;
            $weight = $grade->gradeType->percentage ?? 0;

            $weightedSum += $gradeValue * $weight;
            $totalWeight += $weight;
        }

        if ($totalWeight == 0) {
            return null;
        }

        return round($weightedSum / $totalWeight, 2);
    }

    public function calculateProjectGrade(Collection $weeks): ?float
    {
        if ($weeks->isEmpty()) {
            return null;
        }

        $weightedSum = 0;
        $totalWeight = 0;

        foreach ($weeks as $week) {
            // Make sure relationships are available
            $week->loadMissing('weekType.presenceRule');
            if ($week->weekType && $week->weekType->presenceRule) {
                continue;
            }

            // Use your existing week calculator
            $weekTotal = $this->calculateWeekTotal($week);

            // Skip if this week has no grades
            if (is_null($weekTotal)) {
                continue;
            }

            // Get the weight of this week's type (e.g. Midterm 30%, Final 40%, etc.)
            $weekWeight = $week->weekType->percentage ?? 0;

            $weightedSum += $weekTotal * $weekWeight;
            $totalWeight += $weekWeight;
        }

        if ($totalWeight == 0) {
            return null;
        }

        return round($weightedSum / $totalWeight, 2);
    }

    // public function getWeeklyGrades(Collection $weeks)
    // {
    //     // Make sure relationships are preloaded to reduce N+1 queries
    //     $weeks->loadMissing(['grades.gradeType', 'weekType']);

    //     return $weeks->map(function ($week) {
    //         return [
    //             'week' => $week,
    //             'grade' => $week->totalGrade(),
    //         ];
    //     })->filter(fn ($item) => !is_null($item['grade']))->values();
    // }

}
