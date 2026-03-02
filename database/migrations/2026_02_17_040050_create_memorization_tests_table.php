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
        Schema::create('memorization_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');

            // نطاق الاختبار
            $table->unsignedBigInteger('surah_id');
            $table->unsignedInteger('start_ayah');
            $table->unsignedInteger('end_ayah');

            // النتائج
            $table->unsignedTinyInteger('total_score')->comment('من 100');
            $table->unsignedTinyInteger('memorization_accuracy')->nullable();
            $table->unsignedTinyInteger('tajweed_quality')->nullable();
            $table->unsignedInteger('mistakes_count')->default(0);

            $table->enum('test_type', ['random', 'sequential', 'full_surah'])->default('sequential');
            $table->date('test_date');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('surah_id')->references('id')->on('surahs')->onDelete('cascade');
            $table->index(['student_id', 'test_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorization_tests');
    }
};
