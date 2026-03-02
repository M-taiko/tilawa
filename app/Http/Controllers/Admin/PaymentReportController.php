<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentReportController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function overdueReport()
    {
        $tenantId = session('current_tenant_id');
        $overduePayments = $this->paymentService->getOverduePayments($tenantId);
        $totalOverdue = $overduePayments->sum(fn($p) => $p->remaining_amount);
        return view('admin.reports.payment-overdue', compact('overduePayments', 'totalOverdue'));
    }

    public function monthlyReport(Request $request)
    {
        $tenantId = session('current_tenant_id');
        $month = $request->month ?? Carbon::now()->format('Y-m');
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $statistics = $this->paymentService->getPaymentStatistics($tenantId, $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        return view('admin.reports.payment-monthly', compact('statistics', 'month'));
    }
}
