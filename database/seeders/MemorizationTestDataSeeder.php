<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Session;
use App\Models\Surah;
use App\Models\StudentMemorizationAssignment;
use App\Models\MemorizationTest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemorizationTestDataSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // الحصول على جميع الطلاب
        $students = Student::all();

        if ($students->isEmpty()) {
            $this->command->warn('لا يوجد طلاب في قاعدة البيانات!');
            return;
        }

        $surahs = Surah::all();

        foreach ($students as $student) {
            $this->command->info("إضافة بيانات تجريبية للطالب: {$student->name}");

            // تحديد مستوى عشوائي للطالب (مبتدئ، متوسط، متقدم)
            $level = rand(1, 3);

            switch ($level) {
                case 1: // مبتدئ - حفظ السور القصيرة فقط (جزء عم)
                    $this->seedBeginnerStudent($student, $surahs);
                    break;
                case 2: // متوسط - حفظ حتى منتصف القرآن
                    $this->seedIntermediateStudent($student, $surahs);
                    break;
                case 3: // متقدم - حفظ معظم القرآن
                    $this->seedAdvancedStudent($student, $surahs);
                    break;
            }
        }

        $this->command->info('✅ تم إضافة البيانات التجريبية بنجاح!');
    }

    private function seedBeginnerStudent(Student $student, $surahs)
    {
        // حفظ من سورة الناس (114) إلى سورة النبأ (78) - جزء عم
        $surahsToMemorize = $surahs->whereBetween('id', [78, 114]);

        foreach ($surahsToMemorize as $surah) {
            // إنشاء assignment مكتمل
            StudentMemorizationAssignment::create([
                'tenant_id' => $student->tenant_id,
                'student_id' => $student->id,
                'teacher_id' => $student->class?->teacher_id ?? 1,
                'surah_id' => $surah->id,
                'start_ayah' => 1,
                'end_ayah' => $surah->ayah_count,
                'page_number' => $surah->start_page,
                'status' => 'completed',
                'assigned_date' => now()->subDays(rand(30, 90)),
                'due_date' => now()->subDays(rand(1, 29)),
                'completed_date' => now()->subDays(rand(1, 15)),
                'notes' => 'السورة كاملة',
            ]);

            // إنشاء session للحفظ
            Session::create([
                'tenant_id' => $student->tenant_id,
                'student_id' => $student->id,
                'teacher_id' => $student->class?->teacher_id ?? 1,
                'date' => now()->subDays(rand(1, 60)),
                'session_type' => 'new',
                'attendance_status' => 'present',
                'surah_id' => $surah->id,
                'ayah_from' => 1,
                'ayah_to' => $surah->ayah_count,
                'page_number' => $surah->start_page,
                'ayah_count' => $surah->ayah_count,
                'score' => rand(7, 10),
                'memorization_score' => rand(7, 10),
                'recitation_score' => rand(6, 10),
                'tajweed_score' => rand(6, 9),
            ]);

            // إنشاء اختبار عشوائي لبعض السور
            if (rand(0, 2) == 0) {
                MemorizationTest::create([
                    'tenant_id' => $student->tenant_id,
                    'student_id' => $student->id,
                    'teacher_id' => $student->class?->teacher_id ?? 1,
                    'surah_id' => $surah->id,
                    'start_ayah' => 1,
                    'end_ayah' => $surah->ayah_count,
                    'total_score' => rand(75, 100),
                    'memorization_accuracy' => rand(80, 100),
                    'tajweed_quality' => rand(70, 95),
                    'mistakes_count' => rand(0, 3),
                    'test_type' => 'full_surah',
                    'test_date' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // تحديث موضع الطالب الحالي (بداية سورة المرسلات)
        $student->update([
            'current_surah_id' => 77,
            'current_ayah' => 1,
        ]);
    }

    private function seedIntermediateStudent(Student $student, $surahs)
    {
        // حفظ الجزء الأخير (جزء 30) + بعض من جزء 29
        $completedSurahs = $surahs->where('id', '>=', 78); // من النبأ إلى الناس

        foreach ($completedSurahs as $surah) {
            StudentMemorizationAssignment::create([
                'tenant_id' => $student->tenant_id,
                'student_id' => $student->id,
                'teacher_id' => $student->class?->teacher_id ?? 1,
                'surah_id' => $surah->id,
                'start_ayah' => 1,
                'end_ayah' => $surah->ayah_count,
                'page_number' => $surah->start_page,
                'status' => 'completed',
                'assigned_date' => now()->subDays(rand(60, 180)),
                'completed_date' => now()->subDays(rand(30, 120)),
            ]);

            Session::create([
                'tenant_id' => $student->tenant_id,
                'student_id' => $student->id,
                'teacher_id' => $student->class?->teacher_id ?? 1,
                'date' => now()->subDays(rand(30, 120)),
                'session_type' => 'new',
                'attendance_status' => 'present',
                'surah_id' => $surah->id,
                'ayah_from' => 1,
                'ayah_to' => $surah->ayah_count,
                'page_number' => $surah->start_page,
                'ayah_count' => $surah->ayah_count,
                'score' => rand(7, 10),
                'memorization_score' => rand(7, 10),
                'recitation_score' => rand(7, 10),
                'tajweed_score' => rand(7, 9),
            ]);
        }

        // إضافة تقدم جزئي في سورة الملك (67)
        $mulkSurah = $surahs->find(67);
        if ($mulkSurah) {
            StudentMemorizationAssignment::create([
                'tenant_id' => $student->tenant_id,
                'student_id' => $student->id,
                'teacher_id' => $student->class?->teacher_id ?? 1,
                'surah_id' => $mulkSurah->id,
                'start_ayah' => 1,
                'end_ayah' => 15,
                'page_number' => $mulkSurah->start_page,
                'status' => 'in_progress',
                'assigned_date' => now()->subDays(7),
                'due_date' => now()->addDays(7),
            ]);

            $student->update([
                'current_surah_id' => 67,
                'current_ayah' => 15,
            ]);
        }
    }

    private function seedAdvancedStudent(Student $student, $surahs)
    {
        // حفظ معظم القرآن (حتى سورة الكهف تقريباً)
        $completedSurahs = $surahs->where('id', '>=', 18); // من الكهف إلى الناس

        foreach ($completedSurahs as $surah) {
            StudentMemorizationAssignment::create([
                'tenant_id' => $student->tenant_id,
                'student_id' => $student->id,
                'teacher_id' => $student->class?->teacher_id ?? 1,
                'surah_id' => $surah->id,
                'start_ayah' => 1,
                'end_ayah' => $surah->ayah_count,
                'page_number' => $surah->start_page,
                'status' => 'completed',
                'assigned_date' => now()->subDays(rand(90, 365)),
                'completed_date' => now()->subDays(rand(60, 300)),
            ]);

            Session::create([
                'tenant_id' => $student->tenant_id,
                'student_id' => $student->id,
                'teacher_id' => $student->class?->teacher_id ?? 1,
                'date' => now()->subDays(rand(60, 300)),
                'session_type' => 'new',
                'attendance_status' => 'present',
                'surah_id' => $surah->id,
                'ayah_from' => 1,
                'ayah_to' => $surah->ayah_count,
                'page_number' => $surah->start_page,
                'ayah_count' => $surah->ayah_count,
                'score' => rand(8, 10),
                'memorization_score' => rand(8, 10),
                'recitation_score' => rand(8, 10),
                'tajweed_score' => rand(8, 10),
            ]);

            // اختبارات للسور المهمة
            if ($surah->id % 5 == 0) {
                MemorizationTest::create([
                    'tenant_id' => $student->tenant_id,
                    'student_id' => $student->id,
                    'teacher_id' => $student->class?->teacher_id ?? 1,
                    'surah_id' => $surah->id,
                    'start_ayah' => 1,
                    'end_ayah' => $surah->ayah_count,
                    'total_score' => rand(85, 100),
                    'memorization_accuracy' => rand(90, 100),
                    'tajweed_quality' => rand(85, 100),
                    'mistakes_count' => rand(0, 2),
                    'test_type' => 'full_surah',
                    'test_date' => now()->subDays(rand(30, 200)),
                ]);
            }
        }

        // تقدم جزئي في سورة الإسراء
        $israaSurah = $surahs->find(17);
        if ($israaSurah) {
            $student->update([
                'current_surah_id' => 17,
                'current_ayah' => 50,
            ]);

            StudentMemorizationAssignment::create([
                'tenant_id' => $student->tenant_id,
                'student_id' => $student->id,
                'teacher_id' => $student->class?->teacher_id ?? 1,
                'surah_id' => 17,
                'start_ayah' => 1,
                'end_ayah' => 50,
                'page_number' => $israaSurah->start_page,
                'status' => 'in_progress',
                'assigned_date' => now()->subDays(14),
                'due_date' => now()->addDays(14),
            ]);
        }
    }
}
