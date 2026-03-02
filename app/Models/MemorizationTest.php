<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;

class MemorizationTest extends Model
{
    use TenantScoped;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'teacher_id',
        'surah_id',
        'start_ayah',
        'end_ayah',
        'total_score',
        'memorization_accuracy',
        'tajweed_quality',
        'mistakes_count',
        'test_type',
        'test_date',
        'notes',
    ];

    protected $casts = [
        'test_date' => 'date',
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
     * الحصول على عدد الآيات في الاختبار
     */
    public function getAyahCountAttribute(): int
    {
        return ($this->end_ayah - $this->start_ayah) + 1;
    }
}
