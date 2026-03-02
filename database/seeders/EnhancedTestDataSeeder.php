<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\FoundationSkill;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentFoundationSkillMastery;
use App\Models\StudyClass;
use App\Models\Surah;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EnhancedTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create single comprehensive tenant with all test scenarios
        $this->createComprehensiveTenant();
    }

    private function createComprehensiveTenant(): void
    {
        // Idempotent: Check if tenant already exists
        $tenant = Tenant::firstOrCreate(
            ['name' => 'مركز التلاوة للقرآن الكريم'],
            [
                'is_active' => true,
            ]
        );

        // If tenant already has data, skip to avoid duplicates
        if (TenantUser::where('tenant_id', $tenant->id)->exists()) {
            $this->command->info("Tenant '{$tenant->name}' already has data. Skipping...");
            return;
        }

        // Create admin (active)
        $admin = User::create([
            'name' => 'أحمد المدير',
            'email' => 'donia.a5ra2019@gmail.com',
            'password' => Hash::make('123456789'),
            'global_role' => 'tenant_admin',
            'is_active' => true,
        ]);

        TenantUser::create([
            'tenant_id' => $tenant->id,
            'user_id' => $admin->id,
            'role' => 'tenant_admin',
        ]);

        // Create active teachers for different groups
        $menTeacher1 = $this->createTeacher($tenant, 'محمد المعلم', 'mohammed@tilawa.com', ['men'], true);
        $menTeacher2 = $this->createTeacher($tenant, 'عبدالله المعلم', 'abdullah@tilawa.com', ['men'], true);

        $womenTeacher1 = $this->createTeacher($tenant, 'فاطمة المعلمة', 'fatima@tilawa.com', ['women'], true);
        $womenTeacher2 = $this->createTeacher($tenant, 'عائشة المعلمة', 'aisha@tilawa.com', ['women'], true);

        $kidsTeacher1 = $this->createTeacher($tenant, 'خديجة المعلمة', 'khadija@tilawa.com', ['kids'], true);
        $kidsTeacher2 = $this->createTeacher($tenant, 'زينب المعلمة', 'zainab@tilawa.com', ['kids'], true);

        // Create inactive teacher (for testing)
        $inactiveTeacher = $this->createTeacher($tenant, 'معلم سابق', 'old@tilawa.com', ['men'], false);

        // Create classes with different tracks
        $menClass1 = $this->createClass($tenant, 'حلقة الرجال - الفجر', 'men', 'memorization', $menTeacher1->id);
        $menClass2 = $this->createClass($tenant, 'حلقة الرجال - المغرب', 'men', 'memorization', $menTeacher2->id);

        $womenClass1 = $this->createClass($tenant, 'حلقة النساء - الفجر', 'women', 'memorization', $womenTeacher1->id);
        $womenClass2 = $this->createClass($tenant, 'حلقة النساء - العصر', 'women', 'foundation', $womenTeacher2->id);

        $kidsClassA = $this->createClass($tenant, 'حلقة الأطفال - أ', 'kids', 'memorization', $kidsTeacher1->id);
        $kidsClassB = $this->createClass($tenant, 'حلقة الأطفال - ب', 'kids', 'foundation', $kidsTeacher2->id);
        $kidsClassC = $this->createClass($tenant, 'حلقة الأطفال - ج', 'kids', 'memorization', $kidsTeacher1->id);

        // Create class schedules
        $this->createClassSchedules($tenant, [
            ['class' => $menClass1, 'day' => 'sunday', 'start' => '05:30', 'end' => '07:00', 'location' => 'المسجد'],
            ['class' => $menClass1, 'day' => 'wednesday', 'start' => '05:30', 'end' => '07:00', 'location' => 'المسجد'],
            ['class' => $menClass2, 'day' => 'sunday', 'start' => '18:00', 'end' => '19:30', 'location' => 'المسجد'],
            ['class' => $menClass2, 'day' => 'tuesday', 'start' => '18:00', 'end' => '19:30', 'location' => 'المسجد'],
            ['class' => $womenClass1, 'day' => 'saturday', 'start' => '09:00', 'end' => '10:30', 'location' => 'قاعة النساء'],
            ['class' => $womenClass1, 'day' => 'monday', 'start' => '09:00', 'end' => '10:30', 'location' => 'قاعة النساء'],
            ['class' => $womenClass2, 'day' => 'sunday', 'start' => '15:00', 'end' => '16:30', 'location' => 'قاعة النساء'],
            ['class' => $womenClass2, 'day' => 'thursday', 'start' => '15:00', 'end' => '16:30', 'location' => 'قاعة النساء'],
            ['class' => $kidsClassA, 'day' => 'saturday', 'start' => '16:00', 'end' => '17:00', 'location' => 'قاعة الأطفال'],
            ['class' => $kidsClassA, 'day' => 'monday', 'start' => '16:00', 'end' => '17:00', 'location' => 'قاعة الأطفال'],
            ['class' => $kidsClassB, 'day' => 'sunday', 'start' => '16:00', 'end' => '17:00', 'location' => 'قاعة الأطفال'],
            ['class' => $kidsClassB, 'day' => 'tuesday', 'start' => '16:00', 'end' => '17:00', 'location' => 'قاعة الأطفال'],
            ['class' => $kidsClassC, 'day' => 'saturday', 'start' => '17:00', 'end' => '18:00', 'location' => 'قاعة الأطفال'],
            ['class' => $kidsClassC, 'day' => 'wednesday', 'start' => '17:00', 'end' => '18:00', 'location' => 'قاعة الأطفال'],
        ]);

        // Setup foundation skills
        $this->createFoundationSkills($tenant);

        // Create students with different scenarios
        // MEN GROUP - Active students with memorization track
        for ($i = 1; $i <= 6; $i++) {
            $class = $i <= 3 ? $menClass1 : $menClass2;
            $teacher = $i <= 3 ? $menTeacher1 : $menTeacher2;
            $student = $this->createStudent(
                $tenant,
                "عبدالله الطالب {$i}",
                'men',
                'memorization',
                $class->id,
                now()->subMonths(rand(3, 18)),
                'active'
            );
            $this->createSessions($tenant, $student, $teacher, rand(15, 35));
        }

        // MEN - Graduated student
        $graduatedMen = $this->createStudent(
            $tenant,
            'خالد الخريج',
            'men',
            'memorization',
            null,
            now()->subYears(2),
            'graduated',
            null,
            null,
            now()->subMonths(3)
        );
        $this->createSessions($tenant, $graduatedMen, $menTeacher1, 40, now()->subMonths(6));

        // WOMEN GROUP - Mixed tracks (active)
        for ($i = 1; $i <= 8; $i++) {
            $class = $i <= 4 ? $womenClass1 : $womenClass2;
            $teacher = $i <= 4 ? $womenTeacher1 : $womenTeacher2;
            $track = $i % 3 == 0 ? 'foundation' : 'memorization';

            $student = $this->createStudent(
                $tenant,
                "مريم الطالبة {$i}",
                'women',
                $track,
                $class->id,
                now()->subMonths(rand(2, 15)),
                'active'
            );
            if ($track === 'foundation') {
                $this->createFoundationMastery($tenant, $student);
                $this->createFoundationSessions($tenant, $student, $teacher, rand(10, 30));
            } else {
                $this->createSessions($tenant, $student, $teacher, rand(10, 30));
            }
        }

        // WOMEN - Graduated student
        $graduatedWomen = $this->createStudent(
            $tenant,
            'سارة الخريجة',
            'women',
            'memorization',
            null,
            now()->subYears(1),
            'graduated',
            null,
            null,
            now()->subMonths(1)
        );
        $this->createSessions($tenant, $graduatedWomen, $womenTeacher1, 35, now()->subMonths(4));

        // KIDS GROUP - Active students (mixed tracks)
        for ($i = 1; $i <= 15; $i++) {
            $class = match(true) {
                $i <= 5 => $kidsClassA,
                $i <= 10 => $kidsClassB,
                default => $kidsClassC,
            };
            $teacher = $class->teacher_id === $kidsTeacher1->id ? $kidsTeacher1 : $kidsTeacher2;
            $track = $i % 4 == 0 ? 'foundation' : 'memorization';

            $student = $this->createStudent(
                $tenant,
                "طالب {$i}",
                'kids',
                $track,
                $class->id,
                now()->subMonths(rand(1, 12)),
                'active',
                "ولي الأمر {$i}",
                "0500" . str_pad($i, 6, '0', STR_PAD_LEFT)
            );
            if ($track === 'foundation') {
                $this->createFoundationMastery($tenant, $student);
                $this->createFoundationSessions($tenant, $student, $teacher, rand(8, 28));
            } else {
                $this->createSessions($tenant, $student, $teacher, rand(8, 28));
            }
        }

        // KIDS - Graduated students
        for ($i = 1; $i <= 3; $i++) {
            $student = $this->createStudent(
                $tenant,
                "خريج {$i}",
                'kids',
                'memorization',
                null,
                now()->subYear(),
                'graduated',
                "ولي خريج {$i}",
                "0509999" . str_pad($i, 3, '0', STR_PAD_LEFT),
                now()->subMonths(rand(1, 4))
            );
            $this->createSessions($tenant, $student, $kidsTeacher1, rand(30, 45), now()->subMonths(5));
        }

        // KIDS - Inactive students (dropped out)
        for ($i = 1; $i <= 4; $i++) {
            $student = $this->createStudent(
                $tenant,
                "منقطع {$i}",
                'kids',
                rand(0, 1) ? 'memorization' : 'foundation',
                null,
                now()->subMonths(rand(6, 12)),
                'inactive',
                "ولي منقطع {$i}",
                "0508888" . str_pad($i, 3, '0', STR_PAD_LEFT)
            );
            $this->createSessions($tenant, $student, $kidsTeacher2, rand(3, 10), now()->subMonths(3));
        }
    }

    private function createTeacher(Tenant $tenant, string $name, string $email, array $groups, bool $isActive = true): User
    {
        $teacher = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'global_role' => 'teacher',
            'is_active' => $isActive,
        ]);

        TenantUser::create([
            'tenant_id' => $tenant->id,
            'user_id' => $teacher->id,
            'role' => 'teacher',
        ]);

        return $teacher;
    }

    private function createClass(Tenant $tenant, string $name, string $group, string $track, int $teacherId): StudyClass
    {
        return StudyClass::create([
            'tenant_id' => $tenant->id,
            'name' => $name,
            'group' => $group,
            'track' => $track,
            'teacher_id' => $teacherId,
        ]);
    }

    private function createStudent(
        Tenant $tenant,
        string $name,
        string $group,
        string $track,
        ?int $classId,
        $joinDate,
        string $status = 'active',
        ?string $parentName = null,
        ?string $parentPhone = null,
        $graduationDate = null
    ): Student {
        $data = [
            'tenant_id' => $tenant->id,
            'name' => $name,
            'group' => $group,
            'track' => $track,
            'join_date' => $joinDate,
            'class_id' => $classId,
            'parent_portal_token' => bin2hex(random_bytes(16)),
            'status' => $status,
        ];

        // Kids require parent info
        if ($group === 'kids') {
            $data['parent_name'] = $parentName ?? 'ولي الأمر';
            $data['parent_phone'] = $parentPhone ?? '05' . rand(10000000, 99999999);
        } else {
            // Men/Women require student phone
            $data['student_phone'] = '05' . rand(10000000, 99999999);
        }

        return Student::create($data);
    }

    private function createSessions(Tenant $tenant, Student $student, User $teacher, int $count, $startDate = null): void
    {
        $surahs = Surah::limit(10)->get();
        if ($surahs->isEmpty()) {
            return;
        }

        $startDate = $startDate ?? now()->subMonths(3);

        for ($i = 0; $i < $count; $i++) {
            $surah = $surahs->random();
            $date = (clone $startDate)->addDays($i * rand(1, 3));

            // Mix of session types and attendance
            $types = ['new', 'new', 'revision', 'revision'];
            $attendance = ['present', 'present', 'present', 'present', 'absent', 'excused'];

            $sessionType = $types[array_rand($types)];
            $attendanceStatus = $attendance[array_rand($attendance)];

            $ayahFrom = $attendanceStatus === 'present' ? rand(1, 10) : null;
            $ayahTo = $attendanceStatus === 'present' ? rand(11, 20) : null;
            $ayahCount = ($ayahFrom && $ayahTo) ? ($ayahTo - $ayahFrom + 1) : 0;

            Session::create([
                'tenant_id' => $tenant->id,
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'session_type' => $sessionType,
                'attendance_status' => $attendanceStatus,
                'surah_id' => $attendanceStatus === 'present' ? $surah->id : null,
                'ayah_from' => $ayahFrom,
                'ayah_to' => $ayahTo,
                'ayah_count' => $ayahCount,
                'score' => $attendanceStatus === 'present' ? rand(4, 10) : null,
                'date' => $date->toDateString(),
                'notes' => $i % 5 == 0 ? 'ملاحظات تجريبية' : null,
            ]);
        }
    }

    private function createFoundationSkills(Tenant $tenant): void
    {
        $skills = [
            ['name' => 'الحروف', 'sort_order' => 1],
            ['name' => 'الفتحة', 'sort_order' => 2],
            ['name' => 'الضمة', 'sort_order' => 3],
            ['name' => 'الكسرة', 'sort_order' => 4],
            ['name' => 'التنوين', 'sort_order' => 5],
            ['name' => 'المد', 'sort_order' => 6],
            ['name' => 'السكون', 'sort_order' => 7],
            ['name' => 'الشدة', 'sort_order' => 8],
            ['name' => 'الكلمات', 'sort_order' => 9],
            ['name' => 'الجمل', 'sort_order' => 10],
            ['name' => 'الصفحات', 'sort_order' => 11],
        ];

        foreach ($skills as $skill) {
            FoundationSkill::create([
                'tenant_id' => $tenant->id,
                'name' => $skill['name'],
                'sort_order' => $skill['sort_order'],
                'is_active' => true,
            ]);
        }
    }

    private function createFoundationMastery(Tenant $tenant, Student $student): void
    {
        $skills = FoundationSkill::where('tenant_id', $tenant->id)->get();

        foreach ($skills as $skill) {
            StudentFoundationSkillMastery::create([
                'tenant_id' => $tenant->id,
                'student_id' => $student->id,
                'foundation_skill_id' => $skill->id,
                'mastery_percent' => rand(0, 20) * 5, // Random mastery 0-100 in steps of 5
            ]);
        }
    }

    private function createFoundationSessions(Tenant $tenant, Student $student, User $teacher, int $count, $startDate = null): void
    {
        $skills = FoundationSkill::where('tenant_id', $tenant->id)->get();
        if ($skills->isEmpty()) {
            return;
        }

        $startDate = $startDate ?? now()->subMonths(3);

        for ($i = 0; $i < $count; $i++) {
            $skill = $skills->random();
            $date = (clone $startDate)->addDays($i * rand(1, 3));

            // Mix of attendance
            $attendance = ['present', 'present', 'present', 'present', 'absent', 'excused'];
            $attendanceStatus = $attendance[array_rand($attendance)];

            $masteryProgress = $attendanceStatus === 'present' ? rand(0, 20) * 5 : null;

            Session::create([
                'tenant_id' => $tenant->id,
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'session_type' => 'foundation',
                'attendance_status' => $attendanceStatus,
                'surah_id' => null,
                'ayah_from' => null,
                'ayah_to' => null,
                'ayah_count' => 0,
                'score' => $attendanceStatus === 'present' ? rand(4, 10) : null,
                'foundation_skill_id' => $skill->id,
                'mastery_progress' => $masteryProgress,
                'date' => $date->toDateString(),
                'notes' => $i % 5 == 0 ? 'ملاحظات تأسيسية' : null,
            ]);

            // Update mastery in the mastery table
            if ($attendanceStatus === 'present') {
                StudentFoundationSkillMastery::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'foundation_skill_id' => $skill->id,
                    ],
                    [
                        'tenant_id' => $tenant->id,
                        'mastery_percent' => $masteryProgress ?? 0,
                    ]
                );
            }
        }
    }

    private function createClassSchedules(Tenant $tenant, array $schedules): void
    {
        foreach ($schedules as $schedule) {
            $startTime = \Carbon\Carbon::createFromFormat('H:i', $schedule['start']);
            $endTime = \Carbon\Carbon::createFromFormat('H:i', $schedule['end']);
            $duration = $startTime->diffInMinutes($endTime);

            ClassSchedule::create([
                'tenant_id' => $tenant->id,
                'class_id' => $schedule['class']->id,
                'day_of_week' => $schedule['day'],
                'start_time' => $schedule['start'],
                'end_time' => $schedule['end'],
                'duration_minutes' => $duration,
                'location' => $schedule['location'] ?? null,
                'is_active' => true,
            ]);
        }
    }
}
