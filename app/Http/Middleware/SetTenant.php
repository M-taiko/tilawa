<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SetTenant
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && !$user->isSaasAdmin()) {
            $user->loadMissing('tenants');
            $tenantId = session('current_tenant_id');
            $tenantIds = $user->tenants->pluck('id')->toArray();

            if (!$tenantId || !in_array($tenantId, $tenantIds, true)) {
                $tenantId = $tenantIds[0] ?? null;
                session(['current_tenant_id' => $tenantId]);
            }

            $currentTenant = $user->tenants->firstWhere('id', $tenantId);
            $tenantRole = $currentTenant?->pivot?->role;

            View::share([
                'currentTenant' => $currentTenant,
                'userTenants' => $user->tenants,
                'tenantRole' => $tenantRole,
            ]);
        } elseif ($user && $user->isSaasAdmin()) {
            View::share([
                'currentTenant' => null,
                'userTenants' => collect(),
                'tenantRole' => null,
            ]);
        }

        return $next($request);
    }
}
