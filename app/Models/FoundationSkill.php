<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoundationSkill extends Model
{
    use SoftDeletes, TenantScoped;
    protected $fillable = [
        'tenant_id',
        'name_ar',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function mastery()
    {
        return $this->hasMany(StudentFoundationSkillMastery::class);
    }

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'session_foundation_skills')
            ->withPivot('mastery_percent')
            ->withTimestamps();
    }
}
