<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    protected $fillable = [
        'notes',
        'group_member_id',
        'grader_id'
    ];

    function grades() {
        return $this->hasMany(PersonalGrade::class);
    }
}
