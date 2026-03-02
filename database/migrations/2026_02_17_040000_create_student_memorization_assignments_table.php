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
        Schema::create('student_memorization_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');

            // نطاق الحفظ
            $table->unsignedBigInteger('surah_id');
            $table->unsignedInteger('start_ayah');
            $table->unsignedInteger('end_ayah');
            $table->unsignedSmallInteger('page_number')->nullable();

            // الحالة
            $table->enum('status', ['assigned', 'in_progress', 'completed', 'postponed'])->default('assigned');
            $table->date('assigned_date');
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('surah_id')->references('id')->on('surahs')->onDelete('cascade');
            $table->index(['student_id', 'status']);
            $table->index(['tenant_id', 'assigned_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_memorization_assignments');
    }
};
