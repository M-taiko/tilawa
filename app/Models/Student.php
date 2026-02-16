<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes, TenantScoped;
    protected $fillable = [
        'tenant_id',
        'name',
        'group',
        'track',
        'join_date',
        'parent_name',
        'parent_phone',
        'student_phone',
        'class_id',
        'parent_portal_token',
        'status',
        'graduation_date',
        'current_surah_id',
        'current_ayah',
    ];

    protected $casts = [
        'join_date' => 'date',
        'graduation_date' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function class()
    {
        return $this->belongsTo(StudyClass::class, 'class_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function foundationMastery()
    {
        return $this->hasMany(StudentFoundationSkillMastery::class);
    }

    public function currentSurah()
    {
        return $this->belongsTo(Surah::class, 'current_surah_id');
    }
}
