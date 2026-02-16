<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoundationSkill;
use Illuminate\Http\Request;

class FoundationSkillController extends Controller
{
    public function index()
    {
        $skills = FoundationSkill::where('tenant_id', session('current_tenant_id'))
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.foundation_skills.index', compact('skills'));
    }

    public function create()
    {
        return view('admin.foundation_skills.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        FoundationSkill::create([
            'tenant_id' => session('current_tenant_id'),
            'name_ar' => $validated['name_ar'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool)($validated['is_active'] ?? false),
        ]);

        return redirect()->route('admin.foundation-skills.index')->with('success', 'Foundation skill created');
    }

    public function edit(FoundationSkill $foundationSkill)
    {
        $this->authorizeTenant($foundationSkill);
        return view('admin.foundation_skills.edit', compact('foundationSkill'));
    }

    public function update(Request $request, FoundationSkill $foundationSkill)
    {
        $this->authorizeTenant($foundationSkill);

        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $foundationSkill->update([
            'name_ar' => $validated['name_ar'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool)($validated['is_active'] ?? false),
        ]);

        return redirect()->route('admin.foundation-skills.index')->with('success', 'Foundation skill updated');
    }

    public function destroy(FoundationSkill $foundationSkill)
    {
        $this->authorizeTenant($foundationSkill);
        $foundationSkill->delete();

        return redirect()->route('admin.foundation-skills.index')->with('success', 'Foundation skill deleted');
    }

    private function authorizeTenant(FoundationSkill $foundationSkill): void
    {
        if ($foundationSkill->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }
    }
}
