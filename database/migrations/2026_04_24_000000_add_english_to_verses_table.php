<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            $table->text('verse_text_english')->nullable()->after('verse_text_simple');
        });
    }

    public function down(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            $table->dropColumn('verse_text_english');
        });
    }
};
