<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // جدول رسوم الطلاب (Student Fees)
        Schema::create('student_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('monthly_fee', 10, 2);
            $table->string('currency', 10)->default('SAR');
            $table->date('effective_from');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'student_id', 'is_active']);
            $table->index(['student_id', 'effective_from']);
        });

        // جدول المدفوعات (Payments)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('student_fee_id')->nullable()->constrained('student_fees')->onDelete('set null');
            $table->date('payment_month'); // أول يوم من الشهر
            $table->decimal('amount_due', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'online', 'other'])->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->string('receipt_number', 50)->nullable()->unique();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'payment_status']);
            $table->index(['student_id', 'payment_month']);
            $table->index(['payment_status', 'payment_month']);
        });

        // جدول تذكيرات الدفع (Payment Reminders)
        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('reminder_type', ['email', 'sms', 'notification'])->default('notification');
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('sent_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index('payment_id');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_reminders');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('student_fees');
    }
};
