<?php

namespace App\Http\Controllers;

use App\Models\TenantUser;
use Illuminate\Http\Request;

class TenantSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|integer',
        ]);

        $tenantId = (int)$request->input('tenant_id');
        $user = auth()->user();

        if (!$user || $user->isSaasAdmin()) {
            abort(403, 'Unauthorized');
        }

        $exists = TenantUser::where('tenant_id', $tenantId)
            ->where('user_id', $user->id)
            ->exists();

        if (!$exists) {
            abort(403, 'Unauthorized');
        }

        session(['current_tenant_id' => $tenantId]);

        return back();
    }
}
