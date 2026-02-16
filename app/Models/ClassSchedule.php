<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSchedule extends Model
{
    use SoftDeletes, TenantScoped;
    protected $fillable = [
        'tenant_id',
        'class_id',
        'day_of_week',
        'start_time',
        'end_time',
        'duration_minutes',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function studyClass()
    {
        return $this->belongsTo(StudyClass::class, 'class_id');
    }
}
