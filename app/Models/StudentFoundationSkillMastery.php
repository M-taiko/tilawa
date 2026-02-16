<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;

class StudentFoundationSkillMastery extends Model
{
    use TenantScoped;
    protected $table = 'student_foundation_skill_mastery';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'foundation_skill_id',
        'mastery_percent',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function foundationSkill()
    {
        return $this->belongsTo(FoundationSkill::class);
    }
}
