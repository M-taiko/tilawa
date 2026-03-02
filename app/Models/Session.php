<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use SoftDeletes, TenantScoped;
    protected $fillable = [
        'tenant_id',
        'student_id',
        'teacher_id',
        'session_type',
        'attendance_status',
        'surah_id',
        'ayah_from',
        'ayah_to',
        'page_number',
        'ayah_count',
        'score',
        'memorization_score',
        'recitation_score',
        'tajweed_score',
        'foundation_skill_id',
        'mastery_progress',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function surah()
    {
        return $this->belongsTo(Surah::class);
    }

    public function foundationSkill()
    {
        return $this->belongsTo(FoundationSkill::class);
    }

    public function foundationSkills()
    {
        return $this->belongsToMany(FoundationSkill::class, 'session_foundation_skills')
            ->withPivot('mastery_percent')
            ->withTimestamps();
    }
}
