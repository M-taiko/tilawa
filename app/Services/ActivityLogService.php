<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    /**
     * Log a model action
     */
    public function log(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        // Get tenant_id from session, or from model if available
        $tenantId = session('current_tenant_id');
        if (!$tenantId && $model && isset($model->tenant_id)) {
            $tenantId = $model->tenant_id;
        }

        // Skip logging if no tenant_id (e.g., during seeding without session)
        if (!$tenantId) {
            return;
        }

        ActivityLog::create([
            'tenant_id' => $tenantId,
            'user_id' => auth()->id(),
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log created event
     */
    public function logCreated(Model $model): void
    {
        $this->log('created', $model, null, $model->getAttributes());
    }

    /**
     * Log updated event
     */
    public function logUpdated(Model $model): void
    {
        $oldValues = $model->getOriginal();
        $newValues = $model->getChanges();

        if (empty($newValues)) {
            return; // No changes to log
        }

        $this->log('updated', $model, $oldValues, $newValues);
    }

    /**
     * Log deleted event
     */
    public function logDeleted(Model $model): void
    {
        $this->log('deleted', $model, $model->getAttributes(), null);
    }

    /**
     * Get activity logs for a tenant
     */
    public function getLogsForTenant(int $tenantId, int $perPage = 50)
    {
        return ActivityLog::where('tenant_id', $tenantId)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get activity logs for a specific model
     */
    public function getLogsForModel(Model $model, int $perPage = 20)
    {
        return ActivityLog::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get activity logs for a user
     */
    public function getLogsForUser(int $userId, int $perPage = 50)
    {
        return ActivityLog::where('user_id', $userId)
            ->with('tenant')
            ->latest()
            ->paginate($perPage);
    }
}
