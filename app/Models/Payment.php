<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Payment extends Model
{
    use SoftDeletes, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'student_fee_id',
        'payment_month',
        'amount_due',
        'amount_paid',
        'payment_date',
        'payment_method',
        'payment_status',
        'receipt_number',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'payment_month' => 'date',
        'payment_date' => 'date',
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * علاقة مع Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * علاقة مع Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * علاقة مع StudentFee
     */
    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }

    /**
     * من سجل الدفعة
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * علاقة مع التذكيرات
     */
    public function reminders()
    {
        return $this->hasMany(PaymentReminder::class);
    }

    /**
     * Scope للمدفوعات المتأخرة
     */
    public function scopeOverdue($query)
    {
        return $query->where(function ($q) {
            $q->where('payment_status', 'overdue')
              ->orWhere(function ($q2) {
                  $q2->where('payment_status', 'pending')
                     ->where('payment_month', '<', Carbon::now()->startOfMonth());
              });
        });
    }

    /**
     * Scope للمدفوعات المعلقة
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope للمدفوعات المكتملة
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope حسب الشهر
     */
    public function scopeForMonth($query, $month)
    {
        return $query->whereYear('payment_month', Carbon::parse($month)->year)
                     ->whereMonth('payment_month', Carbon::parse($month)->month);
    }

    /**
     * حساب المبلغ المتبقي
     */
    public function getRemainingAmountAttribute()
    {
        return $this->amount_due - $this->amount_paid;
    }

    /**
     * هل الدفعة مكتملة؟
     */
    public function getIsFullyPaidAttribute()
    {
        return $this->amount_paid >= $this->amount_due;
    }

    /**
     * هل الدفعة متأخرة؟
     */
    public function getIsOverdueAttribute()
    {
        if ($this->payment_status === 'paid') {
            return false;
        }

        return Carbon::parse($this->payment_month)->startOfMonth()->isPast();
    }
}
