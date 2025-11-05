<?php

namespace App\Services;

use App\Models\User;
use App\Models\Week;

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
}
