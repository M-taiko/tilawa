<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::where('tenant_id', session('current_tenant_id'))
            ->with('creator')
            ->latest('start_date')
            ->paginate(20);

        return view('admin.holidays.index', compact('holidays'));
    }

    public function calendar(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $holidays = Holiday::where('tenant_id', session('current_tenant_id'))
            ->whereYear('start_date', '<=', $year)
            ->whereYear('end_date', '>=', $year)
            ->orWhere(function ($query) use ($year, $month) {
                $query->whereYear('start_date', $year)
                    ->whereMonth('start_date', $month);
            })
            ->orWhere(function ($query) use ($year, $month) {
                $query->whereYear('end_date', $year)
                    ->whereMonth('end_date', $month);
            })
            ->get();

        return view('admin.holidays.calendar', compact('holidays', 'year', 'month'));
    }

    public function create()
    {
        return view('admin.holidays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:holiday,vacation,special_event',
            'is_recurring' => 'boolean',
        ]);

        Holiday::create([
            'tenant_id' => session('current_tenant_id'),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'is_recurring' => $request->has('is_recurring'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.holidays.index')
            ->with('success', 'تم إضافة الإجازة بنجاح');
    }

    public function edit(Holiday $holiday)
    {
        $this->authorize($holiday);
        return view('admin.holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $this->authorize($holiday);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:holiday,vacation,special_event',
            'is_recurring' => 'boolean',
        ]);

        $holiday->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'is_recurring' => $request->has('is_recurring'),
        ]);

        return redirect()->route('admin.holidays.index')
            ->with('success', 'تم تحديث الإجازة بنجاح');
    }

    public function destroy(Holiday $holiday)
    {
        $this->authorize($holiday);
        $holiday->delete();

        return redirect()->route('admin.holidays.index')
            ->with('success', 'تم حذف الإجازة بنجاح');
    }

    private function authorize(Holiday $holiday)
    {
        if ($holiday->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }
    }
}
