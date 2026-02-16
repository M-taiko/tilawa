<?php

namespace App\Observers;

use App\Models\Student;
use App\Services\ActivityLogService;

class StudentObserver
{
    protected ActivityLogService $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        $this->activityLog->logCreated($student);
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        $this->activityLog->logUpdated($student);
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        $this->activityLog->logDeleted($student);
    }
}
