<?php

namespace App\Services;

use App\Models\User;
use App\Models\Week;


class PresenceRuleValidator
{
    function isValidPresenceAmount(User $user, Week $week) {
        $user->loadMissing('presences');
        $week->loadMissing('weekType.presenceRule');

        $rule = $week->weekType?->presenceRule;

        // if no rule, then always valid
        if (!$rule) {
            return true;
        }

        $presenceCount = $user->presences()->count();
        $minPresence = $rule->minimum;

        // if the user's presence is more than the minimum required, then valid
        if ($presenceCount >= $minPresence) {
            return true;
        }

        //if its not enough, then the grade is invalid
        return false;
    }
}
