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
        Schema::table('sessions', function (Blueprint $table) {
            // page_number already exists, skip it

            // التقييمات التفصيلية (1-10)
            $table->unsignedTinyInteger('memorization_score')->nullable()->comment('درجة الحفظ')->after('score');
            $table->unsignedTinyInteger('recitation_score')->nullable()->comment('درجة القراءة')->after('memorization_score');
            $table->unsignedTinyInteger('tajweed_score')->nullable()->comment('درجة التجويد')->after('recitation_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Don't drop page_number as it existed before this migration
            $table->dropColumn(['memorization_score', 'recitation_score', 'tajweed_score']);
        });
    }
};
