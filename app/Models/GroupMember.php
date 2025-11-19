<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    function qualifications() {
        return $this->hasMany(Qualification::class);
    }

    function group() {
        return $this->belongsTo(Group::class);
    }
}
