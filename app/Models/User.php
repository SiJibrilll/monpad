<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Asisten;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function mahasiswa_data() {
        return $this->hasOne(Mahasiswa::class);
    }

    public function dosen_data() {
        return $this->hasOne(Dosen::class);
    }

    public function asisten_data() {
        return $this->hasOne(Asisten::class);
    }

    public function scopeMahasiswa($query) {
        return $query->with('mahasiswa_data')->has('mahasiswa_data');
    }

    public function scopeDosen($query) {
        return $query->with('dosen_data')->has('dosen_data');
    }

    public function scopeAsisten($query) {
        return $query->with('asisten_data')->has('asisten_data');
    }

    function projects() {
        return $this->hasMany(Project::class, 'user_id');
    }
}
