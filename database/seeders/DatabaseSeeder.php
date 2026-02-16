<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Quran Surahs data
        $this->call(SurahSeeder::class);

        // Create SaaS Admin (global admin) - idempotent
        User::firstOrCreate(
            ['email' => 'root@tilawa.com'],
            [
                'name' => 'SaaS Admin',
                'password' => Hash::make('password'),
                'global_role' => 'saas_admin',
                'is_active' => true,
            ]
        );

        // Seed comprehensive test data (single tenant with all scenarios)
        $this->call(EnhancedTestDataSeeder::class);
    }
}
