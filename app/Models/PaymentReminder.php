<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    use TenantScoped;

    protected $fillable = [
        'tenant_id',
        'payment_id',
        'student_id',
        'reminder_type',
        'sent_at',
        'sent_by',
        'status',
        'failure_reason',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * علاقة مع Payment
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * علاقة مع Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * علاقة مع Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * من أرسل التذكير
     */
    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Scope للتذكيرات المرسلة
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope للتذكيرات المعلقة
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope للتذكيرات الفاشلة
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
