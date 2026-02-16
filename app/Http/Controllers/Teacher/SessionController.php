<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreSessionRequest;
use App\Http\Requests\Teacher\UpdateSessionRequest;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudyClass;
use App\Models\Surah;
use App\Services\SessionValidationService;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        $teacherId = auth()->id();
        $sessions = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', session('current_tenant_id'))
            ->with(['student', 'surah', 'foundationSkills'])
            ->latest('date')
            ->paginate(20);

        return view('teacher.sessions.index', compact('sessions'));
    }

    public function create()
    {
        $teacherId = auth()->id();
        $students = Student::where('tenant_id', session('current_tenant_id'))
            ->whereHas('class', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->orderBy('name')->get();

        $surahs = Surah::orderBy('id')->get();

        $foundationSkills = \App\Models\FoundationSkill::where('tenant_id', session('current_tenant_id'))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('teacher.sessions.create', compact('students', 'surahs', 'foundationSkills'));
    }

    public function store(StoreSessionRequest $request, SessionValidationService $validationService)
    {
        $validated = $request->validated();

        // Validate session overlaps and holidays
        $validationErrors = $validationService->validateSession([
            'teacher_id' => auth()->id(),
            'student_id' => $validated['student_id'],
            'date' => $validated['date'],
            'tenant_id' => session('current_tenant_id'),
        ]);

        if (!empty($validationErrors)) {
            return back()->withErrors(['date' => implode(' ', $validationErrors)])->withInput();
        }

        $student = Student::where('tenant_id', session('current_tenant_id'))
            ->findOrFail($validated['student_id']);

        $class = StudyClass::where('teacher_id', auth()->id())
            ->where('tenant_id', session('current_tenant_id'))
            ->where('id', $student->class_id)
            ->first();

        if (!$class) {
            abort(403, 'Teacher not assigned to this student');
        }

        $payload = $validated;
        $payload['tenant_id'] = session('current_tenant_id');
        $payload['teacher_id'] = auth()->id();

        // Handle foundation session type
        if ($validated['session_type'] === 'foundation') {
            $skillIds = $validated['foundation_skill_ids'] ?? [];
            $masteryMap = $validated['foundation_mastery'] ?? [];
            if ($validated['attendance_status'] === 'present' && empty($skillIds)) {
                return back()->withErrors(['foundation_skill_ids' => 'اختر مهارة واحدة على الأقل للجلسات التأسيسية'])->withInput();
            }

            // Clear Quran-specific fields for foundation sessions
            $payload['surah_id'] = null;
            $payload['ayah_from'] = null;
            $payload['ayah_to'] = null;
            $payload['ayah_count'] = 0;
            $payload['foundation_skill_id'] = null;
            $payload['mastery_progress'] = null;
        } elseif ($validated['attendance_status'] === 'present') {
            if (empty($validated['surah_id']) || empty($validated['ayah_from']) || empty($validated['ayah_to'])) {
                return back()->withErrors(['surah_id' => 'Surah and ayah range are required for present sessions.'])->withInput();
            }

            $surah = Surah::findOrFail($validated['surah_id']);
            if ($validated['ayah_to'] < $validated['ayah_from']) {
                return back()->withErrors(['ayah_to' => 'Ayah to must be greater than or equal to ayah from.'])->withInput();
            }
            if ($validated['ayah_to'] > $surah->ayah_count) {
                return back()->withErrors(['ayah_to' => 'Ayah to exceeds surah ayah count.'])->withInput();
            }

            $payload['ayah_count'] = ($validated['ayah_to'] - $validated['ayah_from']) + 1;

            if ($validated['session_type'] === 'new') {
                $overlap = Session::where('student_id', $validated['student_id'])
                    ->where('tenant_id', session('current_tenant_id'))
                    ->where('session_type', 'new')
                    ->where('attendance_status', 'present')
                    ->where('surah_id', $validated['surah_id'])
                    ->where(function ($query) use ($validated) {
                        $query->where('ayah_from', '<=', $validated['ayah_to'])
                            ->where('ayah_to', '>=', $validated['ayah_from']);
                    })
                    ->exists();

                if ($overlap) {
                    return back()->withErrors(['ayah_from' => 'Overlapping ayah range already memorized for this surah.'])->withInput();
                }
            }

            $payload['score'] = (int)($validated['score'] ?? 0);
        } else {
            $payload['surah_id'] = null;
            $payload['ayah_from'] = null;
            $payload['ayah_to'] = null;
            $payload['ayah_count'] = 0;
            $payload['score'] = (int)($validated['score'] ?? 0);
        }

        $session = Session::create($payload);

        if ($validated['session_type'] === 'foundation') {
            $skillIds = $validated['foundation_skill_ids'] ?? [];
            $masteryMap = $validated['foundation_mastery'] ?? [];
            $pivotData = [];
            foreach ($skillIds as $skillId) {
                $mastery = isset($masteryMap[$skillId]) ? (int)$masteryMap[$skillId] : 0;
                $pivotData[$skillId] = ['mastery_percent' => $mastery];

                if ($validated['attendance_status'] === 'present') {
                    \App\Models\StudentFoundationSkillMastery::updateOrCreate(
                        [
                            'tenant_id' => session('current_tenant_id'),
                            'student_id' => $validated['student_id'],
                            'foundation_skill_id' => $skillId,
                        ],
                        ['mastery_percent' => $mastery]
                    );
                }
            }

            $session->foundationSkills()->sync($pivotData);
        } else {
            $session->foundationSkills()->sync([]);
        }

        return redirect()->route('teacher.sessions.index')->with('success', 'Session created successfully');
    }

    public function edit(Session $session)
    {
        if ($session->teacher_id !== auth()->id() || $session->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }

        $session->load('foundationSkills');

        $students = Student::where('tenant_id', session('current_tenant_id'))
            ->whereHas('class', function ($query) {
            $query->where('teacher_id', auth()->id());
        })->orderBy('name')->get();

        $surahs = Surah::orderBy('id')->get();

        $foundationSkills = \App\Models\FoundationSkill::where('tenant_id', session('current_tenant_id'))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('teacher.sessions.edit', compact('session', 'students', 'surahs', 'foundationSkills'));
    }

    public function update(UpdateSessionRequest $request, Session $session, SessionValidationService $validationService)
    {
        if ($session->teacher_id !== auth()->id() || $session->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validated();

        // Validate session overlaps and holidays (excluding current session)
        $validationErrors = $validationService->validateSession([
            'teacher_id' => auth()->id(),
            'student_id' => $validated['student_id'],
            'date' => $validated['date'],
            'tenant_id' => session('current_tenant_id'),
        ], $session->id);

        if (!empty($validationErrors)) {
            return back()->withErrors(['date' => implode(' ', $validationErrors)])->withInput();
        }

        $student = Student::where('tenant_id', session('current_tenant_id'))
            ->findOrFail($validated['student_id']);

        $class = StudyClass::where('teacher_id', auth()->id())
            ->where('tenant_id', session('current_tenant_id'))
            ->where('id', $student->class_id)
            ->first();

        if (!$class) {
            abort(403, 'Teacher not assigned to this student');
        }

        $payload = $validated;

        // Handle foundation session type
        if ($validated['session_type'] === 'foundation') {
            $skillIds = $validated['foundation_skill_ids'] ?? [];
            $masteryMap = $validated['foundation_mastery'] ?? [];
            if ($validated['attendance_status'] === 'present' && empty($skillIds)) {
                return back()->withErrors(['foundation_skill_ids' => 'اختر مهارة واحدة على الأقل للجلسات التأسيسية'])->withInput();
            }

            // Clear Quran-specific fields for foundation sessions
            $payload['surah_id'] = null;
            $payload['ayah_from'] = null;
            $payload['ayah_to'] = null;
            $payload['ayah_count'] = 0;
            $payload['foundation_skill_id'] = null;
            $payload['mastery_progress'] = null;
        } elseif ($validated['attendance_status'] === 'present') {
            if (empty($validated['surah_id']) || empty($validated['ayah_from']) || empty($validated['ayah_to'])) {
                return back()->withErrors(['surah_id' => 'Surah and ayah range are required for present sessions.'])->withInput();
            }

            $surah = Surah::findOrFail($validated['surah_id']);
            if ($validated['ayah_to'] < $validated['ayah_from']) {
                return back()->withErrors(['ayah_to' => 'Ayah to must be greater than or equal to ayah from.'])->withInput();
            }
            if ($validated['ayah_to'] > $surah->ayah_count) {
                return back()->withErrors(['ayah_to' => 'Ayah to exceeds surah ayah count.'])->withInput();
            }

            $payload['ayah_count'] = ($validated['ayah_to'] - $validated['ayah_from']) + 1;

            if ($validated['session_type'] === 'new') {
                $overlap = Session::where('student_id', $validated['student_id'])
                    ->where('tenant_id', session('current_tenant_id'))
                    ->where('session_type', 'new')
                    ->where('attendance_status', 'present')
                    ->where('surah_id', $validated['surah_id'])
                    ->where('id', '!=', $session->id)
                    ->where(function ($query) use ($validated) {
                        $query->where('ayah_from', '<=', $validated['ayah_to'])
                            ->where('ayah_to', '>=', $validated['ayah_from']);
                    })
                    ->exists();

                if ($overlap) {
                    return back()->withErrors(['ayah_from' => 'Overlapping ayah range already memorized for this surah.'])->withInput();
                }
            }

            $payload['score'] = (int)($validated['score'] ?? 0);
        } else {
            $payload['surah_id'] = null;
            $payload['ayah_from'] = null;
            $payload['ayah_to'] = null;
            $payload['ayah_count'] = 0;
            $payload['score'] = (int)($validated['score'] ?? 0);
        }

        $session->update($payload);

        if ($validated['session_type'] === 'foundation') {
            $skillIds = $validated['foundation_skill_ids'] ?? [];
            $masteryMap = $validated['foundation_mastery'] ?? [];
            $pivotData = [];
            foreach ($skillIds as $skillId) {
                $mastery = isset($masteryMap[$skillId]) ? (int)$masteryMap[$skillId] : 0;
                $pivotData[$skillId] = ['mastery_percent' => $mastery];

                if ($validated['attendance_status'] === 'present') {
                    \App\Models\StudentFoundationSkillMastery::updateOrCreate(
                        [
                            'tenant_id' => session('current_tenant_id'),
                            'student_id' => $validated['student_id'],
                            'foundation_skill_id' => $skillId,
                        ],
                        ['mastery_percent' => $mastery]
                    );
                }
            }

            $session->foundationSkills()->sync($pivotData);
        }

        return redirect()->route('teacher.sessions.index')->with('success', 'Session updated successfully');
    }
}
