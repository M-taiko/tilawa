<?php

namespace Database\Seeders;

use App\Models\Verse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        echo "Fetching English translations from api.alquran.cloud...\n";

        $response = file_get_contents('https://api.alquran.cloud/v1/quran/en.sahih');
        if (!$response) {
            $this->command->error('Failed to fetch translations from API');
            return;
        }

        $data = json_decode($response, true);
        if ($data['code'] !== 200 || !isset($data['data']['surahs'])) {
            $this->command->error('Invalid API response');
            return;
        }

        $surahs = $data['data']['surahs'];
        $totalVerses = 0;
        $updatedVerses = 0;

        // Loop through all surahs and verses
        foreach ($surahs as $surah) {
            $surahId = $surah['number'];
            $verses = $surah['ayahs'] ?? [];

            foreach ($verses as $ayah) {
                $totalVerses++;
                $verseNumber = $ayah['numberInSurah'];
                $englishText = $ayah['text'] ?? '';

                if ($englishText) {
                    // Update or create the verse with English translation
                    $updated = Verse::where('surah_id', $surahId)
                        ->where('verse_number', $verseNumber)
                        ->update(['verse_text_english' => $englishText]);

                    if ($updated) {
                        $updatedVerses++;
                    }
                }

                // Progress every 500 verses
                if ($totalVerses % 500 === 0) {
                    echo "Progress: $totalVerses verses processed...\n";
                }
            }
        }

        echo "\n✓ Translation seeding completed!\n";
        echo "  Total verses in API: $totalVerses\n";
        echo "  Verses updated: $updatedVerses\n";
    }
}
