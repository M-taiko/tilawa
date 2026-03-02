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

    /**
     * علاقة مع رسوم الطالب
     */
    public function fees()
    {
        return $this->hasMany(StudentFee::class);
    }

    /**
     * الرسوم النشطة
     */
    public function activeFee()
    {
        return $this->hasOne(StudentFee::class)->where('is_active', true)->latest('effective_from');
    }

    /**
     * علاقة مع المدفوعات
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * علاقة مع المقاطع المعينة للحفظ
     */
    public function memorizations()
    {
        return $this->hasMany(StudentMemorizationAssignment::class);
    }

    /**
     * المقطع الحالي قيد الحفظ
     */
    public function currentMemorization()
    {
        return $this->hasOne(StudentMemorizationAssignment::class)
            ->where('status', 'in_progress')
            ->latest('assigned_date');
    }

    /**
     * علاقة مع اختبارات الحفظ
     */
    public function tests()
    {
        return $this->hasMany(MemorizationTest::class);
    }

    /**
     * الحصول على رقم الصفحة الحالية للطالب
     */
    public function getCurrentPageNumber(): ?int
    {
        if (!$this->current_surah_id || !$this->current_ayah) {
            return null;
        }

        $verse = Verse::where('surah_id', $this->current_surah_id)
            ->where('verse_number', $this->current_ayah)
            ->first(['page_number']);

        return $verse?->page_number;
    }
}
