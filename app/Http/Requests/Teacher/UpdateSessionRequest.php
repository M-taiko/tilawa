<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'session_type' => 'required|in:new,revision,foundation',
            'attendance_status' => 'required|in:present,absent,excused',
            'surah_id' => 'nullable|exists:surahs,id',
            'ayah_from' => 'nullable|integer|min:1',
            'ayah_to' => 'nullable|integer|min:1',
            'score' => 'nullable|integer|min:0|max:10',
            'foundation_skill_id' => 'nullable|exists:foundation_skills,id',
            'mastery_progress' => 'nullable|integer|min:0|max:100',
            'foundation_skill_ids' => 'nullable|array',
            'foundation_skill_ids.*' => 'integer|exists:foundation_skills,id',
            'foundation_mastery' => 'nullable|array',
            'foundation_mastery.*' => 'nullable|integer|min:0|max:100',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'الطالب',
            'session_type' => 'نوع الجلسة',
            'attendance_status' => 'حالة الحضور',
            'surah_id' => 'السورة',
            'ayah_from' => 'من الآية',
            'ayah_to' => 'إلى الآية',
            'score' => 'التقييم',
            'foundation_skill_id' => 'المهارة التأسيسية',
            'mastery_progress' => 'نسبة الإتقان',
            'foundation_skill_ids' => 'المهارات التأسيسية',
            'foundation_mastery' => 'نسب الإتقان',
            'date' => 'التاريخ',
            'notes' => 'ملاحظات',
        ];
    }
}
