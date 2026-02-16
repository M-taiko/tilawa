<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudyClass;
use Illuminate\Http\Request;

class BulkActionController extends Controller
{
    /**
     * Bulk update students status
     */
    public function updateStudentsStatus(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|in:active,graduated,inactive',
        ]);

        $tenantId = session('current_tenant_id');

        $count = Student::where('tenant_id', $tenantId)
            ->whereIn('id', $validated['student_ids'])
            ->update([
                'status' => $validated['status'],
                'graduation_date' => $validated['status'] === 'graduated' ? now() : null,
            ]);

        return back()->with('success', "تم تحديث حالة {$count} طالب بنجاح");
    }

    /**
     * Bulk assign students to a class
     */
    public function assignStudentsToClass(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $tenantId = session('current_tenant_id');

        // Verify class belongs to tenant
        $class = StudyClass::where('id', $validated['class_id'])
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        // Get selected students
        $students = Student::where('tenant_id', $tenantId)
            ->whereIn('id', $validated['student_ids'])
            ->get();

        // Validate all students have the same group and track
        $groups = $students->pluck('group')->unique();
        $tracks = $students->pluck('track')->unique();

        if ($groups->count() > 1) {
            return back()->withErrors([
                'bulk_action' => 'لا يمكن تعيين طلاب من مجموعات مختلفة (رجال/نساء/أطفال) للحلقة نفسها. يرجى اختيار طلاب من مجموعة واحدة فقط.'
            ]);
        }

        if ($tracks->count() > 1) {
            return back()->withErrors([
                'bulk_action' => 'لا يمكن تعيين طلاب من مسارات مختلفة (حفظ/تأسيس) للحلقة نفسها. يرجى اختيار طلاب من مسار واحد فقط.'
            ]);
        }

        $studentGroup = $groups->first();
        $studentTrack = $tracks->first();

        // Validate class matches student group and track
        if ($class->group !== $studentGroup) {
            $groupLabels = [
                'men' => 'رجال',
                'women' => 'نساء',
                'kids' => 'أطفال',
            ];
            $studentGroupLabel = $groupLabels[$studentGroup] ?? $studentGroup;
            $classGroupLabel = $groupLabels[$class->group] ?? $class->group;

            return back()->withErrors([
                'bulk_action' => "الحلقة المختارة للمجموعة ({$classGroupLabel}) بينما الطلاب من مجموعة ({$studentGroupLabel}). يرجى اختيار حلقة متوافقة."
            ]);
        }

        if ($class->track !== $studentTrack) {
            $trackLabels = [
                'memorization' => 'حفظ',
                'foundation' => 'تأسيس',
            ];
            $studentTrackLabel = $trackLabels[$studentTrack] ?? $studentTrack;
            $classTrackLabel = $trackLabels[$class->track] ?? $class->track;

            return back()->withErrors([
                'bulk_action' => "الحلقة المختارة للمسار ({$classTrackLabel}) بينما الطلاب من مسار ({$studentTrackLabel}). يرجى اختيار حلقة متوافقة."
            ]);
        }

        // All validations passed, proceed with assignment
        $count = Student::where('tenant_id', $tenantId)
            ->whereIn('id', $validated['student_ids'])
            ->update(['class_id' => $class->id]);

        return back()->with('success', "تم تعيين {$count} طالب للحلقة {$class->name} بنجاح");
    }

    /**
     * Bulk delete students
     */
    public function deleteStudents(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $tenantId = session('current_tenant_id');

        $count = Student::where('tenant_id', $tenantId)
            ->whereIn('id', $validated['student_ids'])
            ->delete();

        return back()->with('success', "تم حذف {$count} طالب بنجاح");
    }

    /**
     * Bulk update teachers status
     */
    public function updateTeachersStatus(Request $request)
    {
        $validated = $request->validate([
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $tenantId = session('current_tenant_id');

        // Get teachers that belong to this tenant
        $teacherIds = \App\Models\TenantUser::where('tenant_id', $tenantId)
            ->where('role', 'teacher')
            ->whereIn('user_id', $validated['teacher_ids'])
            ->pluck('user_id');

        $isActive = $validated['status'] === 'active';

        $count = \App\Models\User::whereIn('id', $teacherIds)
            ->update(['is_active' => $isActive]);

        $statusText = $isActive ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$statusText} {$count} معلم بنجاح");
    }

    /**
     * Bulk delete teachers
     */
    public function deleteTeachers(Request $request)
    {
        $validated = $request->validate([
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        $tenantId = session('current_tenant_id');

        // Delete only the tenant-teacher relationship, not the user itself
        $count = \App\Models\TenantUser::where('tenant_id', $tenantId)
            ->where('role', 'teacher')
            ->whereIn('user_id', $validated['teacher_ids'])
            ->delete();

        return back()->with('success', "تم حذف {$count} معلم من المركز بنجاح");
    }

    /**
     * Bulk delete classes
     */
    public function deleteClasses(Request $request)
    {
        $validated = $request->validate([
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $tenantId = session('current_tenant_id');

        $count = StudyClass::where('tenant_id', $tenantId)
            ->whereIn('id', $validated['class_ids'])
            ->delete();

        return back()->with('success', "تم حذف {$count} حلقة بنجاح");
    }

    /**
     * Bulk update classes status
     */
    public function updateClassesStatus(Request $request)
    {
        $validated = $request->validate([
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:classes,id',
            'is_active' => 'required|boolean',
        ]);

        $tenantId = session('current_tenant_id');

        $count = StudyClass::where('tenant_id', $tenantId)
            ->whereIn('id', $validated['class_ids'])
            ->update(['is_active' => $validated['is_active']]);

        $status = $validated['is_active'] ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$status} {$count} حلقة بنجاح");
    }
}
