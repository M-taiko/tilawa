<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

trait TenantScoped
{
    /**
     * Boot the tenant scoped trait.
     */
    protected static function bootTenantScoped(): void
    {
        // Add global scope to automatically filter by tenant
        static::addGlobalScope(new TenantScope());

        // Automatically set tenant_id when creating new records
        static::creating(function (Model $model) {
            if (!$model->tenant_id && session('current_tenant_id')) {
                $model->tenant_id = session('current_tenant_id');
            }
        });
    }
}
