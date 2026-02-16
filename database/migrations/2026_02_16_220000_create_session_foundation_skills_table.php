<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_foundation_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('sessions')->onDelete('cascade');
            $table->foreignId('foundation_skill_id')->constrained('foundation_skills')->onDelete('cascade');
            $table->unsignedTinyInteger('mastery_percent')->default(0);
            $table->timestamps();

            $table->unique(['session_id', 'foundation_skill_id'], 'session_foundation_skill_unique');
            $table->index('foundation_skill_id');
        });

        // Backfill from legacy session columns if present
        $legacySessions = DB::table('sessions')
            ->select('id', 'foundation_skill_id', 'mastery_progress', 'created_at', 'updated_at')
            ->whereNotNull('foundation_skill_id')
            ->get();

        if ($legacySessions->isNotEmpty()) {
            $rows = $legacySessions->map(function ($session) {
                return [
                    'session_id' => $session->id,
                    'foundation_skill_id' => $session->foundation_skill_id,
                    'mastery_percent' => (int)($session->mastery_progress ?? 0),
                    'created_at' => $session->created_at,
                    'updated_at' => $session->updated_at,
                ];
            })->all();

            DB::table('session_foundation_skills')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('session_foundation_skills');
    }
};
