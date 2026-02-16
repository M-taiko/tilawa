<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentFoundationSkillMastery;
use Illuminate\Support\Str;

class StudentService
{
    /**
     * Create a new student with mastery data
     */
    public function createStudent(array $data, int $tenantId): Student
    {
        $studentData = [
            'tenant_id' => $tenantId,
            'name' => $data['name'],
            'group' => $data['group'],
            'track' => $data['track'],
            'join_date' => $data['join_date'],
            'parent_name' => $data['parent_name'] ?? null,
            'parent_phone' => $data['parent_phone'] ?? null,
            'student_phone' => $data['student_phone'] ?? null,
            'class_id' => $data['class_id'] ?? null,
            'parent_portal_token' => Str::random(32),
            'status' => 'active',
        ];

        if ($data['track'] === 'memorization') {
            $studentData['current_surah_id'] = $data['current_surah_id'] ?? null;
            $studentData['current_ayah'] = $data['current_ayah'] ?? null;
        }

        $student = Student::create($studentData);

        // Handle foundation mastery if track is foundation
        if ($data['track'] === 'foundation' && !empty($data['mastery'])) {
            $this->updateFoundationMastery($student, $data['mastery']);
        }

        return $student;
    }

    /**
     * Update student with mastery data
     */
    public function updateStudent(Student $student, array $data): Student
    {
        $studentData = [
            'name' => $data['name'],
            'group' => $data['group'],
            'track' => $data['track'],
            'join_date' => $data['join_date'],
            'parent_name' => $data['parent_name'] ?? null,
            'parent_phone' => $data['parent_phone'] ?? null,
            'student_phone' => $data['student_phone'] ?? null,
            'class_id' => $data['class_id'] ?? null,
            'status' => $data['status'] ?? $student->status,
            'graduation_date' => $data['graduation_date'] ?? $student->graduation_date,
        ];

        if ($data['track'] === 'memorization') {
            $studentData['current_surah_id'] = $data['current_surah_id'] ?? null;
            $studentData['current_ayah'] = $data['current_ayah'] ?? null;
        }

        $student->update($studentData);

        // Handle foundation mastery if track is foundation
        if ($data['track'] === 'foundation' && !empty($data['mastery'])) {
            $this->updateFoundationMastery($student, $data['mastery']);
        }

        return $student;
    }

    /**
     * Update foundation skill mastery for a student
     */
    public function updateFoundationMastery(Student $student, array $masteryData): void
    {
        foreach ($masteryData as $skillId => $masteryPercent) {
            if ($masteryPercent === null) {
                continue;
            }

            StudentFoundationSkillMastery::updateOrCreate(
                [
                    'tenant_id' => $student->tenant_id,
                    'student_id' => $student->id,
                    'foundation_skill_id' => $skillId,
                ],
                [
                    'mastery_percent' => $masteryPercent,
                ]
            );
        }
    }

    /**
     * Graduate a student
     */
    public function graduateStudent(Student $student): Student
    {
        $student->update([
            'status' => 'graduated',
            'graduation_date' => now(),
        ]);

        return $student;
    }

    /**
     * Regenerate parent token for a student
     */
    public function regenerateToken(Student $student): Student
    {
        $student->update([
            'parent_portal_token' => Str::random(32),
        ]);

        return $student;
    }
}
