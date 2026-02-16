<?php

namespace App\Services;

use App\Models\ClassSchedule;
use App\Models\Session;
use App\Models\Student;
use Carbon\Carbon;

class SessionValidationService
{
    /**
     * Check if teacher has overlapping sessions
     */
    public function hasTeacherOverlap(int $teacherId, string $date, int $tenantId, ?int $excludeSessionId = null): bool
    {
        $query = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('date', $date);

        if ($excludeSessionId) {
            $query->where('id', '!=', $excludeSessionId);
        }

        return $query->exists();
    }

    /**
     * Check if student has overlapping sessions
     */
    public function hasStudentOverlap(int $studentId, string $date, ?int $excludeSessionId = null): bool
    {
        $query = Session::where('student_id', $studentId)
            ->where('date', $date);

        if ($excludeSessionId) {
            $query->where('id', '!=', $excludeSessionId);
        }

        return $query->exists();
    }

    /**
     * Check if date is a holiday
     */
    public function isHoliday(string $date, int $tenantId): bool
    {
        $holiday = \App\Models\Holiday::where('tenant_id', $tenantId)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        return $holiday !== null;
    }

    /**
     * Get teacher's available time slots for a date
     */
    public function getTeacherAvailableSlots(int $teacherId, string $date, int $tenantId): array
    {
        $dayOfWeek = Carbon::parse($date)->format('l'); // Monday, Tuesday, etc.
        $dayOfWeekLower = strtolower($dayOfWeek);

        // Get teacher's scheduled classes for this day
        $schedules = ClassSchedule::whereHas('studyClass', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
        ->where('tenant_id', $tenantId)
        ->where('day_of_week', $dayOfWeekLower)
        ->where('is_active', true)
        ->get();

        // Get already booked sessions
        $bookedSessions = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('date', $date)
            ->get();

        $availableSlots = [];
        foreach ($schedules as $schedule) {
            $slotBooked = false;
            foreach ($bookedSessions as $session) {
                // If there's already a session at this time, mark as booked
                // Note: This is simplified - you may want to compare exact times
                $slotBooked = true;
                break;
            }

            if (!$slotBooked) {
                $availableSlots[] = [
                    'class_id' => $schedule->class_id,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'location' => $schedule->location,
                ];
            }
        }

        return $availableSlots;
    }

    /**
     * Validate session before creation/update
     */
    public function validateSession(array $data, ?int $excludeSessionId = null): array
    {
        $errors = [];

        // Check teacher overlap
        if ($this->hasTeacherOverlap($data['teacher_id'], $data['date'], $data['tenant_id'], $excludeSessionId)) {
            $errors[] = 'المعلم لديه جلسة أخرى في نفس اليوم';
        }

        // Check student overlap
        if ($this->hasStudentOverlap($data['student_id'], $data['date'], $excludeSessionId)) {
            $errors[] = 'الطالب لديه جلسة أخرى في نفس اليوم';
        }

        // Check if it's a holiday
        if ($this->isHoliday($data['date'], $data['tenant_id'])) {
            $errors[] = 'هذا اليوم عطلة رسمية';
        }

        return $errors;
    }
}
