<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;

class StudentTransfer extends Model
{
    use TenantScoped;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'from_class_id',
        'to_class_id',
        'from_teacher_id',
        'to_teacher_id',
        'reason',
        'notes',
        'transferred_by',
        'transferred_at',
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromClass()
    {
        return $this->belongsTo(StudyClass::class, 'from_class_id');
    }

    public function toClass()
    {
        return $this->belongsTo(StudyClass::class, 'to_class_id');
    }

    public function fromTeacher()
    {
        return $this->belongsTo(User::class, 'from_teacher_id');
    }

    public function toTeacher()
    {
        return $this->belongsTo(User::class, 'to_teacher_id');
    }

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
}
