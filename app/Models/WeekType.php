<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeekType extends Model
{
    protected $fillable = [
        'name',
        'percentage'
    ];
}
