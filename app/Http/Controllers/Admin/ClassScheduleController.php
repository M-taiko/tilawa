<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\StudyClass;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function index()
    {
        $tenantId = session('current_tenant_id');

        $schedules = ClassSchedule::where('tenant_id', $tenantId)
            ->with('studyClass')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(20);

        return view('admin.schedules.index', compact('schedules'));
    }

    public function show(ClassSchedule $schedule)
    {
        if ($schedule->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }

        $schedule->load('studyClass.teacher');

        return response()->json($schedule);
    }

    public function calendar(Request $request)
    {
        $tenantId = session('current_tenant_id');

        $query = ClassSchedule::where('tenant_id', $tenantId)
            ->with('studyClass.teacher')
            ->orderBy('day_of_week')
            ->orderBy('start_time');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('studyClass', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('group')) {
            $query->whereHas('studyClass', function ($q) use ($request) {
                $q->where('group', $request->group);
            });
        }

        if ($request->filled('track')) {
            $query->whereHas('studyClass', function ($q) use ($request) {
                $q->where('track', $request->track);
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $allSchedules = $query->get();

        $schedules = [
            'sunday' => $allSchedules->where('day_of_week', 'sunday')->values(),
            'monday' => $allSchedules->where('day_of_week', 'monday')->values(),
            'tuesday' => $allSchedules->where('day_of_week', 'tuesday')->values(),
            'wednesday' => $allSchedules->where('day_of_week', 'wednesday')->values(),
            'thursday' => $allSchedules->where('day_of_week', 'thursday')->values(),
            'friday' => $allSchedules->where('day_of_week', 'friday')->values(),
            'saturday' => $allSchedules->where('day_of_week', 'saturday')->values(),
        ];

        $dayNames = [
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت',
        ];

        $stats = [
            'total' => $allSchedules->count(),
            'active' => $allSchedules->where('is_active', true)->count(),
            'by_day' => collect($schedules)->map(fn ($day) => $day->count()),
            'busiest_day' => collect($schedules)->sortByDesc(fn ($day) => $day->count())->keys()->first(),
            'total_hours' => round($allSchedules->sum('duration_minutes') / 60, 1),
        ];

        $groups = ['men' => 'رجال', 'women' => 'نساء', 'kids' => 'أطفال'];
        $tracks = ['memorization' => 'حفظ', 'foundation' => 'أساسيات'];

        return view('admin.schedules.calendar', compact(
            'schedules',
            'dayNames',
            'stats',
            'groups',
            'tracks'
        ));
    }

    public function create()
    {
        $tenantId = session('current_tenant_id');

        $classes = StudyClass::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.schedules.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $tenantId = session('current_tenant_id');

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Check for conflicts
        $conflict = ClassSchedule::where('tenant_id', $tenantId)
            ->where('class_id', $validated['class_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'يوجد تعارض مع موعد آخر لنفس الحلقة في نفس اليوم'])->withInput();
        }

        // Calculate duration
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);
        $duration = $startTime->diffInMinutes($endTime);

        $payload = $validated;
        $payload['tenant_id'] = $tenantId;
        $payload['duration_minutes'] = $duration;
        $payload['is_active'] = $request->has('is_active');

        ClassSchedule::create($payload);

        return redirect()->route('admin.schedules.index')->with('success', 'تم إضافة الموعد بنجاح');
    }

    public function edit(ClassSchedule $schedule)
    {
        if ($schedule->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }

        $classes = StudyClass::where('tenant_id', session('current_tenant_id'))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.schedules.edit', compact('schedule', 'classes'));
    }

    public function update(Request $request, ClassSchedule $schedule)
    {
        if ($schedule->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }

        $tenantId = session('current_tenant_id');

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Check for conflicts (excluding current schedule)
        $conflict = ClassSchedule::where('tenant_id', $tenantId)
            ->where('class_id', $validated['class_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $schedule->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'يوجد تعارض مع موعد آخر لنفس الحلقة في نفس اليوم'])->withInput();
        }

        // Calculate duration
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);
        $duration = $startTime->diffInMinutes($endTime);

        $payload = $validated;
        $payload['duration_minutes'] = $duration;
        $payload['is_active'] = $request->has('is_active');

        $schedule->update($payload);

        return redirect()->route('admin.schedules.index')->with('success', 'تم تحديث الموعد بنجاح');
    }

    public function destroy(ClassSchedule $schedule)
    {
        if ($schedule->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }

        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'تم حذف الموعد بنجاح');
    }
}
