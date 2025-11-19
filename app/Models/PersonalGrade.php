<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalGrade extends Model
{
    protected $fillable = [
        'personal_grade_type_id',
        'grade'
    ];

    function gradeType() {
        return $this->BelongsTo(PersonalGradeType::class);
    }
}
