<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class isMahasiswa implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::find($value);
        

        if (!$user) {
            $fail('user not found');
        }

        if (!$user->hasRole('mahasiswa')) {
            $fail('user must be an mahasiswa');
        }
    }
}
