<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected int $tenantId;
    protected array $filters;

    public function __construct(int $tenantId, array $filters = [])
    {
        $this->tenantId = $tenantId;
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Student::where('tenant_id', $this->tenantId)
            ->with('class');

        // Apply filters
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['group'])) {
            $query->where('group', $this->filters['group']);
        }

        if (!empty($this->filters['track'])) {
            $query->where('track', $this->filters['track']);
        }

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->filters['search'] . '%')
                  ->orWhere('parent_name', 'like', '%' . $this->filters['search'] . '%')
                  ->orWhere('parent_phone', 'like', '%' . $this->filters['search'] . '%');
            });
        }

        return $query->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'المجموعة',
            'المسار',
            'الحلقة',
            'الحالة',
            'تاريخ الانضمام',
            'تاريخ التخرج',
            'ولي الأمر',
            'هاتف ولي الأمر',
            'هاتف الطالب',
        ];
    }

    public function map($student): array
    {
        return [
            $student->name,
            $this->translateGroup($student->group),
            $this->translateTrack($student->track),
            $student->class?->name ?? '-',
            $this->translateStatus($student->status),
            $student->join_date?->format('Y-m-d') ?? '-',
            $student->graduation_date?->format('Y-m-d') ?? '-',
            $student->parent_name ?? '-',
            $student->parent_phone ?? '-',
            $student->student_phone ?? '-',
        ];
    }

    private function translateGroup(?string $group): string
    {
        return match($group) {
            'men' => 'رجال',
            'women' => 'نساء',
            'kids' => 'أطفال',
            default => '-',
        };
    }

    private function translateTrack(?string $track): string
    {
        return match($track) {
            'memorization' => 'حفظ',
            'foundation' => 'تأسيس',
            default => '-',
        };
    }

    private function translateStatus(?string $status): string
    {
        return match($status) {
            'active' => 'نشط',
            'graduated' => 'خريج',
            'inactive' => 'غير نشط',
            default => '-',
        };
    }
}
