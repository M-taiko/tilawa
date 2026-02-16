<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentTransfer;
use App\Models\StudyClass;

class StudentTransferService
{
    /**
     * Transfer student to a new class/teacher
     */
    public function transferStudent(
        Student $student,
        ?int $toClassId,
        string $reason,
        ?string $notes = null
    ): StudentTransfer {
        $fromClass = $student->class;
        $toClass = $toClassId ? StudyClass::find($toClassId) : null;

        // Create transfer record
        $transfer = StudentTransfer::create([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'from_class_id' => $fromClass?->id,
            'to_class_id' => $toClass?->id,
            'from_teacher_id' => $fromClass?->teacher_id,
            'to_teacher_id' => $toClass?->teacher_id,
            'reason' => $reason,
            'notes' => $notes,
            'transferred_by' => auth()->id(),
            'transferred_at' => now(),
        ]);

        // Update student's class
        $student->update([
            'class_id' => $toClassId,
        ]);

        return $transfer;
    }

    /**
     * Get transfer history for a student
     */
    public function getTransferHistory(Student $student)
    {
        return StudentTransfer::where('student_id', $student->id)
            ->with(['fromClass', 'toClass', 'fromTeacher', 'toTeacher', 'transferredBy'])
            ->orderBy('transferred_at', 'desc')
            ->get();
    }
}
