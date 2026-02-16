<?php

namespace App\Exports;

use App\Models\Session;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SessionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected int $tenantId;
    protected ?int $teacherId;
    protected array $filters;

    public function __construct(int $tenantId, ?int $teacherId = null, array $filters = [])
    {
        $this->tenantId = $tenantId;
        $this->teacherId = $teacherId;
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Session::where('tenant_id', $this->tenantId)
            ->with(['student', 'teacher', 'surah', 'foundationSkills']);

        if ($this->teacherId) {
            $query->where('teacher_id', $this->teacherId);
        }

        // Apply filters
        if (!empty($this->filters['student_id'])) {
            $query->where('student_id', $this->filters['student_id']);
        }

        if (!empty($this->filters['session_type'])) {
            $query->where('session_type', $this->filters['session_type']);
        }

        if (!empty($this->filters['attendance_status'])) {
            $query->where('attendance_status', $this->filters['attendance_status']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->where('date', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->where('date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('date', 'desc');
    }

    public function headings(): array
    {
        return [
            'التاريخ',
            'الطالب',
            'المعلم',
            'النوع',
            'الحضور',
            'المحتوى',
            'الكمية',
            'التقييم',
            'ملاحظات',
        ];
    }

    public function map($session): array
    {
        $content = '';
        $quantity = '';

        if ($session->session_type === 'foundation') {
            $skillNames = $session->foundationSkills
                ->map(fn ($skill) => $skill->name_ar ?? $skill->name)
                ->filter()
                ->values();
            $content = $skillNames->isNotEmpty() ? $skillNames->implode('، ') : '-';
            $masteryAvg = $session->foundationSkills->isNotEmpty()
                ? round($session->foundationSkills->avg(fn ($skill) => (int) $skill->pivot->mastery_percent))
                : ($session->mastery_progress ?? 0);
            $quantity = $masteryAvg . '%';
        } else {
            $content = $session->surah ?
                $session->surah->name_ar . ' (' . $session->ayah_from . '-' . $session->ayah_to . ')' :
                '-';
            $quantity = $session->ayah_count . ' آية';
        }

        return [
            $session->date->format('Y-m-d'),
            $session->student?->name ?? '-',
            $session->teacher?->name ?? '-',
            $this->translateSessionType($session->session_type),
            $this->translateAttendance($session->attendance_status),
            $content,
            $quantity,
            $session->score ?? '-',
            $session->notes ?? '-',
        ];
    }

    private function translateSessionType(?string $type): string
    {
        return match($type) {
            'new' => 'حفظ جديد',
            'revision' => 'مراجعة',
            'foundation' => 'تأسيس',
            default => '-',
        };
    }

    private function translateAttendance(?string $status): string
    {
        return match($status) {
            'present' => 'حاضر',
            'absent' => 'غائب',
            'excused' => 'معتذر',
            default => '-',
        };
    }
}
