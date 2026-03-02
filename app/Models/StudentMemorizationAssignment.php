<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;

class StudentMemorizationAssignment extends Model
{
    use TenantScoped;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'teacher_id',
        'surah_id',
        'start_ayah',
        'end_ayah',
        'page_number',
        'status',
        'assigned_date',
        'due_date',
        'completed_date',
        'notes',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
        'completed_date' => 'date',
    ];

    /**
     * علاقة مع الطالب
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * علاقة مع المعلم
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * علاقة مع السورة
     */
    public function surah()
    {
        return $this->belongsTo(Surah::class);
    }

    /**
     * الحصول على عدد الآيات المعينة
     */
    public function getAyahCountAttribute(): int
    {
        return ($this->end_ayah - $this->start_ayah) + 1;
    }

    /**
     * التحقق من تأخر المقطع عن موعده
     */
    public function isOverdue(): bool
    {
        return $this->due_date &&
               $this->status !== 'completed' &&
               $this->due_date->isPast();
    }
}
