<?php

namespace App\Http\Middleware;

use App\Models\TenantUser;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        if ($user->isSaasAdmin() && in_array('saas_admin', $roles, true)) {
            return $next($request);
        }

        $tenantId = session('current_tenant_id');
        if (!$tenantId) {
            abort(403, 'Unauthorized');
        }

        $membership = TenantUser::where('tenant_id', $tenantId)
            ->where('user_id', $user->id)
            ->first();

        if (!$membership || !in_array($membership->role, $roles, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
