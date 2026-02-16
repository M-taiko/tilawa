<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'group' => 'required|in:men,women,kids',
            'track' => 'required|in:memorization,foundation',
            'join_date' => 'required|date',
            // Kids: require parent info
            'parent_name' => 'required_if:group,kids|nullable|string|max:255',
            'parent_phone' => 'required_if:group,kids|nullable|string|max:20',
            // Men/Women: student phone is optional
            'student_phone' => 'nullable|string|max:20',
            'class_id' => 'nullable|exists:classes,id',
            'current_surah_id' => 'nullable|exists:surahs,id',
            'current_ayah' => 'nullable|integer|min:1',
            'mastery' => 'nullable|array',
            'mastery.*' => 'nullable|integer|min:0|max:100',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم الطالب',
            'group' => 'المجموعة',
            'track' => 'المسار',
            'join_date' => 'تاريخ الانضمام',
            'parent_name' => 'اسم ولي الأمر',
            'parent_phone' => 'هاتف ولي الأمر',
            'student_phone' => 'هاتف الطالب',
            'class_id' => 'الحلقة',
            'current_surah_id' => 'السورة الحالية',
            'current_ayah' => 'الآية الحالية',
        ];
    }
}
