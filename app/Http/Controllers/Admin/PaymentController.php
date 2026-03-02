<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $tenantId = session('current_tenant_id');
        $query = Payment::where('tenant_id', $tenantId)->with(['student', 'studentFee']);

        if ($request->status) $query->where('payment_status', $request->status);
        if ($request->month) $query->forMonth($request->month);
        if ($request->student_id) $query->where('student_id', $request->student_id);

        $payments = $query->orderBy('payment_month', 'desc')->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $tenantId = session('current_tenant_id');
        $students = Student::where('tenant_id', $tenantId)->where('status', 'active')->get();
        return view('admin.payments.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required',
            'notes' => 'nullable|string',
        ]);

        $payment = Payment::findOrFail($validated['payment_id']);
        $this->paymentService->recordPayment($payment, $validated);

        return redirect()->route('admin.payments.show', $payment)->with('success', 'تم التسجيل');
    }

    public function show(Payment $payment)
    {
        $payment->load(['student', 'studentFee']);
        return view('admin.payments.show', compact('payment'));
    }

    public function generateMonthly(Request $request)
    {
        $validated = $request->validate(['month' => 'required|date_format:Y-m']);
        $result = $this->paymentService->generateMonthlyPayments(session('current_tenant_id'), $validated['month']);
        return back()->with('success', "تم توليد {$result['generated']} مدفوعة");
    }

    public function edit(string $id) { }
    public function update(Request $request, string $id) { }
    public function destroy(string $id) { }
}
