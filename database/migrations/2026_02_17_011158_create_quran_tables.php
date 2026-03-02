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
        // 1. جدول الآيات (verses) - 6,236 آية
        Schema::create('verses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surah_id');
            $table->unsignedInteger('verse_number');
            $table->text('verse_text');                      // النص العثماني
            $table->text('verse_text_simple')->nullable();   // للبحث (بدون تشكيل)
            $table->unsignedSmallInteger('page_number');     // 1-604
            $table->unsignedTinyInteger('juz_number');       // 1-30
            $table->unsignedTinyInteger('hizb_number')->nullable();  // 1-60
            $table->boolean('sajda')->default(false);        // هل فيها سجدة
            $table->timestamps();

            // Indexes
            $table->unique(['surah_id', 'verse_number']);
            $table->index('page_number');
            $table->index('juz_number');

            // Foreign key
            $table->foreign('surah_id')->references('id')->on('surahs')->onDelete('cascade');
        });

        // Add fulltext index separately (MySQL limitation)
        DB::statement('ALTER TABLE verses ADD FULLTEXT INDEX idx_verses_fulltext (verse_text)');

        // 2. جدول الصفحات (quran_pages) - 604 صفحة
        Schema::create('quran_pages', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary();   // 1-604
            $table->unsignedTinyInteger('juz_number');
            $table->unsignedBigInteger('first_surah_id');
            $table->unsignedInteger('first_verse_number');
            $table->unsignedBigInteger('last_surah_id');
            $table->unsignedInteger('last_verse_number');
            $table->timestamps();

            // Indexes
            $table->index('juz_number');
        });

        // 3. جدول الأجزاء (juzs) - 30 جزء
        Schema::create('juzs', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();    // 1-30
            $table->string('name_arabic');                    // الجزء الأول، الثاني...
            $table->unsignedBigInteger('start_surah_id');
            $table->unsignedInteger('start_verse_number');
            $table->unsignedBigInteger('end_surah_id');
            $table->unsignedInteger('end_verse_number');
            $table->timestamps();
        });

        // 4. تحديث جدول surahs
        Schema::table('surahs', function (Blueprint $table) {
            $table->unsignedSmallInteger('start_page')->after('ayah_count')->nullable();
            $table->unsignedSmallInteger('end_page')->after('start_page')->nullable();
            $table->unsignedTinyInteger('juz_start')->after('end_page')->nullable();

            // Indexes
            $table->index(['start_page', 'end_page']);
            $table->index('juz_start');
        });

        // 5. تحديث جدول sessions
        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedSmallInteger('page_number')->after('ayah_to')->nullable();

            // Index
            $table->index('page_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف الحقول المضافة لـ sessions
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['page_number']);
            $table->dropColumn('page_number');
        });

        // حذف الحقول المضافة لـ surahs
        Schema::table('surahs', function (Blueprint $table) {
            $table->dropIndex(['start_page', 'end_page']);
            $table->dropIndex(['juz_start']);
            $table->dropColumn(['start_page', 'end_page', 'juz_start']);
        });

        // حذف الجداول
        Schema::dropIfExists('juzs');
        Schema::dropIfExists('quran_pages');
        Schema::dropIfExists('verses');
    }
};
