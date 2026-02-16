<?php

namespace App\Services;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    /**
     * Generate completion certificate for a student
     */
    public function generateCompletionCertificate(Student $student): \Illuminate\Http\Response
    {
        $data = [
            'student' => $student,
            'tenant' => $student->tenant,
            'date' => now()->format('Y-m-d'),
            'hijri_date' => $this->getHijriDate(),
        ];

        $pdf = PDF::loadView('certificates.completion', $data);

        return $pdf->download("certificate_{$student->name}.pdf");
    }

    /**
     * Generate memorization milestone certificate
     */
    public function generateMilestoneCertificate(Student $student, int $juzCount): \Illuminate\Http\Response
    {
        $data = [
            'student' => $student,
            'tenant' => $student->tenant,
            'juz_count' => $juzCount,
            'date' => now()->format('Y-m-d'),
            'hijri_date' => $this->getHijriDate(),
        ];

        $pdf = PDF::loadView('certificates.milestone', $data);

        return $pdf->download("certificate_milestone_{$student->name}.pdf");
    }

    /**
     * Generate excellence certificate
     */
    public function generateExcellenceCertificate(Student $student, string $reason): \Illuminate\Http\Response
    {
        $data = [
            'student' => $student,
            'tenant' => $student->tenant,
            'reason' => $reason,
            'date' => now()->format('Y-m-d'),
            'hijri_date' => $this->getHijriDate(),
        ];

        $pdf = PDF::loadView('certificates.excellence', $data);

        return $pdf->download("certificate_excellence_{$student->name}.pdf");
    }

    /**
     * Get Hijri date (simplified - you may want to use a proper Hijri calendar library)
     */
    private function getHijriDate(): string
    {
        // This is a simplified version. For accurate Hijri dates, use a library like "umm-alqura"
        return now()->format('Y-m-d') . ' هـ';
    }
}
