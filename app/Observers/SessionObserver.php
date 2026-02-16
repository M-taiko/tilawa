<?php

namespace App\Observers;

use App\Models\Session;
use App\Services\ActivityLogService;

class SessionObserver
{
    protected ActivityLogService $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Handle the Session "created" event.
     */
    public function created(Session $session): void
    {
        $this->activityLog->logCreated($session);
    }

    /**
     * Handle the Session "updated" event.
     */
    public function updated(Session $session): void
    {
        $this->activityLog->logUpdated($session);
    }

    /**
     * Handle the Session "deleted" event.
     */
    public function deleted(Session $session): void
    {
        $this->activityLog->logDeleted($session);
    }
}
