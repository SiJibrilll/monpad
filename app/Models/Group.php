<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'project_id'
    ];

    function project() {
        return $this->belongsTo(Project::class);
    }

    function members() {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id');
    }
}
