<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Student;
use App\Services\QuranProgressService;

class ParentController extends Controller
{
    public function __construct(
        private QuranProgressService $progressService
    ) {}

    public function show(string $token)
    {
        $student = Student::where('parent_portal_token', $token)->firstOrFail();

        // Block access for inactive students
        if ($student->status === 'inactive') {
            abort(403, 'هذا الرابط غير نشط. يرجى التواصل مع المركز.');
        }

        // Allow access for active and graduated students

        $sessions = Session::where('student_id', $student->id)
            ->with('surah')
            ->latest('date')
            ->paginate(20);

        $stats = Session::where('student_id', $student->id)
            ->selectRaw("
                SUM(CASE WHEN session_type = 'new' AND attendance_status = 'present' THEN ayah_count ELSE 0 END) as total_ayahs,
                AVG(score) as avg_score,
                COUNT(*) as total_sessions,
                SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present_sessions,
                SUM(CASE WHEN attendance_status = 'absent' THEN 1 ELSE 0 END) as absent_sessions,
                SUM(CASE WHEN attendance_status = 'excused' THEN 1 ELSE 0 END) as excused_sessions
            ")
            ->first();

        $totalAyahs = (int)($stats->total_ayahs ?? 0);
        $memorizedPercent = round(($totalAyahs / 6236) * 100, 2);
        $avgScore = $stats->avg_score;
        $totalSessions = (int)($stats->total_sessions ?? 0);
        $presentSessions = (int)($stats->present_sessions ?? 0);
        $absentSessions = (int)($stats->absent_sessions ?? 0);
        $excusedSessions = (int)($stats->excused_sessions ?? 0);
        
        // Monthly Data (Last 6 Months)
        $startMonth = now()->startOfMonth()->subMonths(5)->toDateString();

        $monthlyAyahs = Session::where('student_id', $student->id)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, SUM(ayah_count) as total")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        $monthlyReviewAyahs = Session::where('student_id', $student->id)
            ->where('session_type', 'revision')
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, SUM(ayah_count) as total")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        $monthlyScores = Session::where('student_id', $student->id)
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, AVG(score) as avg_score")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        $monthlyAttendance = Session::where('student_id', $student->id)
            ->where('date', '>=', $startMonth)
            ->selectRaw("
                DATE_FORMAT(date, '%Y-%m-01') as month_start,
                SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance_status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN attendance_status = 'excused' THEN 1 ELSE 0 END) as excused_count
            ")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        $monthLabels = [];
        $ayahsData = [];
        $reviewData = [];
        $avgScoreData = [];
        $presentData = [];
        $absentData = [];
        $excusedData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
            $monthStart = $date->toDateString();
            $monthLabels[] = $date->format('Y-m');

            $ayahsData[] = (int)($monthlyAyahs->firstWhere('month_start', $monthStart)?->total ?? 0);
            $reviewData[] = (int)($monthlyReviewAyahs->firstWhere('month_start', $monthStart)?->total ?? 0);
            $avgScoreData[] = round((float)($monthlyScores->firstWhere('month_start', $monthStart)?->avg_score ?? 0), 2);
            $presentData[] = (int)($monthlyAttendance->firstWhere('month_start', $monthStart)?->present_count ?? 0);
            $absentData[] = (int)($monthlyAttendance->firstWhere('month_start', $monthStart)?->absent_count ?? 0);
            $excusedData[] = (int)($monthlyAttendance->firstWhere('month_start', $monthStart)?->excused_count ?? 0);
        }

        // Load foundation skills mastery if student is on foundation track
        $foundationSkills = [];
        if ($student->track === 'foundation') {
            // Eager load mastery to avoid N+1 queries
            $masteryData = \App\Models\StudentFoundationSkillMastery::where('student_id', $student->id)
                ->pluck('mastery_percent', 'foundation_skill_id');

            $foundationSkills = \App\Models\FoundationSkill::where('tenant_id', $student->tenant_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(function ($skill) use ($masteryData) {
                    return [
                        'name' => $skill->name_ar,
                        'mastery' => $masteryData[$skill->id] ?? 0,
                    ];
                });
        }

        // Get Quran progress map
        $progressMap = $this->progressService->getProgressMap($student);
        $statistics = $this->progressService->getOverallStatistics($student);

        // Mark as public access
        $isPublic = true;

        return view('student.progress', compact(
            'student',
            'sessions',
            'totalAyahs',
            'memorizedPercent',
            'avgScore',
            'totalSessions',
            'presentSessions',
            'absentSessions',
            'excusedSessions',
            'monthLabels',
            'ayahsData',
            'reviewData',
            'avgScoreData',
            'presentData',
            'absentData',
            'excusedData',
            'foundationSkills',
            'progressMap',
            'statistics',
            'isPublic'
        ));
    }
}
