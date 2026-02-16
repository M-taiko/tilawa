<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudyClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected int $tenantId;
    protected array $errors = [];
    protected int $imported = 0;

    public function __construct(int $tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $classId = null;
                if (!empty($row['class_name'])) {
                    $class = StudyClass::where('tenant_id', $this->tenantId)
                        ->where('name', $row['class_name'])
                        ->first();
                    $classId = $class?->id;
                }

                Student::create([
                    'tenant_id' => $this->tenantId,
                    'name' => $row['name'],
                    'group' => $this->mapGroup($row['group']),
                    'track' => $this->mapTrack($row['track']),
                    'join_date' => $row['join_date'] ?? now(),
                    'parent_name' => $row['parent_name'] ?? null,
                    'parent_phone' => $row['parent_phone'] ?? null,
                    'student_phone' => $row['student_phone'] ?? null,
                    'class_id' => $classId,
                    'parent_portal_token' => Str::random(32),
                    'status' => 'active',
                ]);

                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'group' => 'required|string',
            'track' => 'required|string',
        ];
    }

    private function mapGroup(string $group): string
    {
        $map = [
            'رجال' => 'men',
            'نساء' => 'women',
            'أطفال' => 'kids',
        ];

        return $map[$group] ?? $group;
    }

    private function mapTrack(string $track): string
    {
        $map = [
            'حفظ' => 'memorization',
            'تأسيس' => 'foundation',
        ];

        return $map[$track] ?? $track;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getImported(): int
    {
        return $this->imported;
    }
}
