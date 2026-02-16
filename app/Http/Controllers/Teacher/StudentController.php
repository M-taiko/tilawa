<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudyClass;

class StudentController extends Controller
{
    public function index()
    {
        $teacherId = auth()->id();
        $tenantId = session('current_tenant_id');

        $classIds = StudyClass::where('tenant_id', $tenantId)
            ->where('teacher_id', $teacherId)
            ->pluck('id');

        $students = Student::whereIn('class_id', $classIds)
            ->with('class')
            ->orderBy('name')
            ->paginate(20);

        return view('teacher.students.index', compact('students'));
    }
}
