<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFee extends Model
{
    use SoftDeletes, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'teacher_id',
        'monthly_fee',
        'currency',
        'effective_from',
        'notes',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'monthly_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * علاقة مع Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * علاقة مع Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * علاقة مع Teacher (User)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * علاقة مع المدفوعات
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * من أنشأ الرسوم
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope للحصول على الرسوم النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope للحصول على الرسوم حسب الطالب
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}
