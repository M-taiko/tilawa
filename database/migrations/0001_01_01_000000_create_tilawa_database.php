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
        // Tenants table
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('database_name')->nullable();
            $table->text('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('created_at');
        });

        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('global_role', ['saas_admin', 'tenant_admin', 'teacher'])->default('teacher');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('global_role');
            $table->index('created_at');
        });

        // Tenant-User pivot table
        Schema::create('tenant_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['tenant_admin', 'teacher'])->default('teacher');
            $table->timestamps();

            $table->unique(['tenant_id', 'user_id']);
        });

        // Surahs table (Quran chapters reference) - Must be created before students
        Schema::create('surahs', function (Blueprint $table) {
            $table->id();
            $table->string('name_arabic');
            $table->string('name_english');
            $table->unsignedInteger('number');
            $table->unsignedInteger('ayah_count');
            $table->timestamps();
        });

        // Classes table
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('group', ['men', 'women', 'kids'])->comment('Student group type');
            $table->enum('track', ['memorization', 'foundation'])->comment('Learning track type');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('group');
            $table->index('track');
            $table->index(['tenant_id', 'is_active']);
            $table->index(['tenant_id', 'group', 'track']);
        });

        // Students table
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('parent_name')->nullable(); // For kids only
            $table->string('parent_phone')->nullable(); // For kids only
            $table->string('student_phone')->nullable(); // For men/women only
            $table->text('notes')->nullable();
            $table->string('parent_portal_token')->unique();
            $table->enum('group', ['men', 'women', 'kids'])->default('men');
            $table->enum('track', ['memorization', 'foundation'])->default('memorization');
            $table->date('join_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated'])->default('active');
            $table->foreignId('current_surah_id')->nullable()->constrained('surahs')->onDelete('set null');
            $table->unsignedInteger('current_ayah')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('group');
            $table->index('track');
            $table->index('created_at');
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'group']);
            $table->index(['tenant_id', 'track']);
        });

        // Foundation Skills table (MUST be before sessions)
        Schema::create('foundation_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index(['tenant_id', 'is_active']);
            $table->index(['tenant_id', 'sort_order']);
        });

        // Sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->enum('session_type', ['new', 'revision', 'foundation'])->default('new');
            $table->enum('attendance_status', ['present', 'absent', 'excused'])->default('present');
            $table->foreignId('surah_id')->nullable()->constrained('surahs')->onDelete('set null');
            $table->unsignedInteger('ayah_from')->nullable();
            $table->unsignedInteger('ayah_to')->nullable();
            $table->unsignedInteger('ayah_count')->default(0);
            $table->unsignedTinyInteger('score')->nullable();
            $table->foreignId('foundation_skill_id')->nullable()->constrained('foundation_skills')->onDelete('set null');
            $table->unsignedInteger('mastery_progress')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('created_at');
            $table->index(['tenant_id', 'date']);
            $table->index('session_type');
            $table->index('attendance_status');
        });

        // Student Foundation Skill Mastery table
        Schema::create('student_foundation_skill_mastery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('foundation_skill_id')->constrained('foundation_skills')->onDelete('cascade');
            $table->unsignedTinyInteger('mastery_percent')->default(0);
            $table->timestamps();

            // Indexes with custom names to avoid MySQL 64-char limit
            $table->index(['student_id', 'mastery_percent'], 'sfm_student_mastery_idx');
            $table->index('created_at', 'sfm_created_at_idx');
            $table->unique(['student_id', 'foundation_skill_id'], 'sfm_student_skill_unique');
        });

        // Activity Logs table (Audit Trail)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['tenant_id', 'created_at']);
            $table->index('user_id');
            $table->index(['model_type', 'model_id']);
            $table->index('action');
        });

        // Announcements table
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('target_audience', ['all', 'teachers', 'students'])->default('all');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['tenant_id', 'is_active']);
            $table->index('target_audience');
            $table->index('priority');
        });

        // Holidays table
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['holiday', 'vacation', 'special_event'])->default('holiday');
            $table->boolean('is_recurring')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['tenant_id', 'start_date', 'end_date']);
            $table->index('type');
            $table->index('is_recurring');
        });

        // Student Transfers table
        Schema::create('student_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('from_class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->foreignId('to_class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('transferred_by')->constrained('users')->onDelete('cascade');
            $table->enum('reason', [
                'level_advancement',
                'teacher_request',
                'parent_request',
                'schedule_conflict',
                'performance_issues',
                'other'
            ])->default('other');
            $table->text('notes')->nullable();
            $table->timestamp('transferred_at');
            $table->timestamps();

            // Indexes
            $table->index(['student_id', 'transferred_at']);
            $table->index(['tenant_id', 'transferred_at']);
            $table->index('reason');
        });

        // Class Schedules table
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->enum('day_of_week', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedTinyInteger('duration_minutes')->default(60);
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['tenant_id', 'class_id', 'day_of_week']);
            $table->index('is_active');
            $table->index(['tenant_id', 'is_active']);
            $table->index('created_at');
            $table->unique(['class_id', 'day_of_week', 'start_time'], 'class_day_time_unique');
        });

        // Password reset tokens table (Laravel default)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // User sessions table (Laravel auth sessions) - renamed to avoid conflict with teaching sessions
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop in reverse order of creation
        Schema::dropIfExists('user_sessions'); // Laravel auth sessions
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('class_schedules');
        Schema::dropIfExists('student_transfers');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('student_foundation_skill_mastery');
        Schema::dropIfExists('foundation_skills');
        Schema::dropIfExists('sessions'); // Teaching sessions
        Schema::dropIfExists('students');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('surahs');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tenants');
    }
};
