<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    /**
     * Show import students page
     */
    public function showStudentsImport()
    {
        return view('admin.import.students');
    }

    /**
     * Import students from Excel/CSV
     */
    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // 5MB max
        ]);

        $tenantId = session('current_tenant_id');

        try {
            $import = new StudentsImport($tenantId);
            Excel::import($import, $request->file('file'));

            $imported = $import->getImported();
            $errors = $import->getErrors();

            if (!empty($errors)) {
                return back()
                    ->with('warning', "تم استيراد {$imported} طالب. هناك {count($errors)} خطأ.")
                    ->with('import_errors', $errors);
            }

            return back()->with('success', "تم استيراد {$imported} طالب بنجاح");
        } catch (\Exception $e) {
            return back()
                ->withErrors(['file' => 'فشل الاستيراد: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Download sample template
     */
    public function downloadStudentsTemplate()
    {
        $headers = [
            'name',
            'group',
            'track',
            'join_date',
            'parent_name',
            'parent_phone',
            'student_phone',
            'class_name',
        ];

        $sampleData = [
            [
                'محمد أحمد',
                'رجال',
                'حفظ',
                now()->format('Y-m-d'),
                'أحمد محمد',
                '0501234567',
                '0509876543',
                'حلقة الفجر',
            ],
        ];

        $filename = 'students_template.csv';
        $handle = fopen('php://temp', 'w');

        // Add BOM for UTF-8
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        fputcsv($handle, $headers);

        // Add sample data
        foreach ($sampleData as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
