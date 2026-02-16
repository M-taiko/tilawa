<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Skip scope if user is a SaaS admin
        if (auth()->check() && auth()->user()->isSaasAdmin()) {
            return;
        }

        // Apply tenant filter if tenant is set in session
        if ($tenantId = session('current_tenant_id')) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }
}
