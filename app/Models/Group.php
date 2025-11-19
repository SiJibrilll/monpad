<?php

namespace App\Models;

use App\Models\Concerns\Finalizable;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use Finalizable;

    protected $fillable = [
        'name',
        'project_id'
    ];

     public function getFinalizationSource()
    {
        return $this->project;
    }

    function project() {
        return $this->belongsTo(Project::class);
    }

    function members() {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id');
    }
}
