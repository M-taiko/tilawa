<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'global_role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class)
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'teacher_id');
    }

    public function classes()
    {
        return $this->hasMany(StudyClass::class, 'teacher_id');
    }

    public function isSaasAdmin(): bool
    {
        return $this->global_role === 'saas_admin';
    }
}
