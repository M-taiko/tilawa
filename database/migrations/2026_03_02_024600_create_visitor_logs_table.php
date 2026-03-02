<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 45)->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('country_name', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('page', 255)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('device_type', 20)->nullable(); // mobile, tablet, desktop
            $table->timestamp('visited_at')->useCurrent();
            $table->index('visited_at');
            $table->index('country_code');
            $table->index('ip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
