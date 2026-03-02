<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentFee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * توليد المدفوعات الشهرية تلقائياً لجميع الطلاب النشطين الذين لديهم رسوم
     */
    public function generateMonthlyPayments(int $tenantId, string $month): array
    {
        $paymentMonth = Carbon::parse($month)->startOfMonth();
        $generatedCount = 0;
        $skippedCount = 0;

        // الحصول على جميع الطلاب النشطين الذين لديهم رسوم نشطة
        $students = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereHas('activeFee')
            ->with('activeFee')
            ->get();

        foreach ($students as $student) {
            $activeFee = $student->activeFee;

            if (!$activeFee) {
                continue;
            }

            // التحقق من عدم وجود مدفوعات لنفس الشهر
            $existingPayment = Payment::where('tenant_id', $tenantId)
                ->where('student_id', $student->id)
                ->whereYear('payment_month', $paymentMonth->year)
                ->whereMonth('payment_month', $paymentMonth->month)
                ->first();

            if ($existingPayment) {
                $skippedCount++;
                continue;
            }

            // إنشاء المدفوعة
            Payment::create([
                'tenant_id' => $tenantId,
                'student_id' => $student->id,
                'student_fee_id' => $activeFee->id,
                'payment_month' => $paymentMonth,
                'amount_due' => $activeFee->monthly_fee,
                'amount_paid' => 0,
                'payment_status' => 'pending',
                'recorded_by' => auth()->id(),
            ]);

            $generatedCount++;
        }

        return [
            'generated' => $generatedCount,
            'skipped' => $skippedCount,
            'total_students' => $students->count(),
        ];
    }

    /**
     * تسجيل دفعة
     */
    public function recordPayment(Payment $payment, array $data): Payment
    {
        DB::beginTransaction();

        try {
            // تحديث البيانات
            $payment->update([
                'amount_paid' => $data['amount_paid'],
                'payment_date' => $data['payment_date'] ?? now(),
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
                'recorded_by' => auth()->id(),
            ]);

            // تحديث الحالة بناءً على المبلغ المدفوع
            if ($payment->amount_paid >= $payment->amount_due) {
                $payment->payment_status = 'paid';
            } elseif ($payment->amount_paid > 0) {
                $payment->payment_status = 'partial';
            }

            // توليد رقم إيصال إذا لم يكن موجوداً
            if (!$payment->receipt_number && $payment->payment_status === 'paid') {
                $payment->receipt_number = $this->generateReceiptNumber($payment);
            }

            $payment->save();

            DB::commit();

            return $payment->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * توليد رقم إيصال فريد
     */
    protected function generateReceiptNumber(Payment $payment): string
    {
        $date = $payment->payment_date ?? now();
        $prefix = 'REC';
        $year = $date->format('Y');
        $month = $date->format('m');

        // عداد تسلسلي لهذا الشهر
        $count = Payment::where('tenant_id', $payment->tenant_id)
            ->whereNotNull('receipt_number')
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->count() + 1;

        return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $count);
    }

    /**
     * الحصول على ملخص مدفوعات الطالب
     */
    public function getStudentPaymentSummary(Student $student): array
    {
        $payments = $student->payments()->with('studentFee')->orderBy('payment_month', 'desc')->get();

        $totalDue = $payments->sum('amount_due');
        $totalPaid = $payments->sum('amount_paid');
        $totalOverdue = $payments->where('is_overdue', true)->sum(function ($payment) {
            return $payment->remaining_amount;
        });

        return [
            'total_due' => $totalDue,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalDue - $totalPaid,
            'total_overdue' => $totalOverdue,
            'paid_count' => $payments->where('payment_status', 'paid')->count(),
            'pending_count' => $payments->where('payment_status', 'pending')->count(),
            'overdue_count' => $payments->where('is_overdue', true)->count(),
            'payments' => $payments,
        ];
    }

    /**
     * الحصول على المدفوعات المتأخرة
     */
    public function getOverduePayments(int $tenantId)
    {
        return Payment::where('tenant_id', $tenantId)
            ->overdue()
            ->with(['student', 'studentFee'])
            ->orderBy('payment_month', 'asc')
            ->get();
    }

    /**
     * حساب إحصائيات المدفوعات
     */
    public function getPaymentStatistics(int $tenantId, string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $payments = Payment::where('tenant_id', $tenantId)
            ->whereBetween('payment_month', [$start, $end])
            ->get();

        return [
            'total_expected' => $payments->sum('amount_due'),
            'total_collected' => $payments->sum('amount_paid'),
            'total_remaining' => $payments->sum(fn($p) => $p->remaining_amount),
            'paid_count' => $payments->where('payment_status', 'paid')->count(),
            'pending_count' => $payments->where('payment_status', 'pending')->count(),
            'partial_count' => $payments->where('payment_status', 'partial')->count(),
            'overdue_count' => $payments->where('is_overdue', true)->count(),
            'collection_rate' => $payments->sum('amount_due') > 0
                ? ($payments->sum('amount_paid') / $payments->sum('amount_due')) * 100
                : 0,
        ];
    }

    /**
     * تحديث حالات المدفوعات المتأخرة
     */
    public function updateOverdueStatuses(int $tenantId): int
    {
        $count = Payment::where('tenant_id', $tenantId)
            ->where('payment_status', 'pending')
            ->where('payment_month', '<', Carbon::now()->startOfMonth())
            ->update(['payment_status' => 'overdue']);

        return $count;
    }
}
