<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class QuranDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('بدء استيراد بيانات القرآن الكريم...');

        // 1. استيراد الآيات من AlQuran Cloud API
        $this->importVerses();

        // 2. إنشاء بيانات الصفحات
        $this->generatePages();

        // 3. إنشاء بيانات الأجزاء
        $this->generateJuzs();

        // 4. تحديث جدول السور
        $this->updateSurahs();

        $this->command->info('✅ تم استيراد بيانات القرآن الكريم بنجاح!');
    }

    /**
     * استيراد الآيات من API
     */
    private function importVerses(): void
    {
        $this->command->info('📖 جاري استيراد الآيات...');

        try {
            // استدعاء API للحصول على القرآن الكامل بالرسم العثماني
            $response = Http::timeout(120)->get('http://api.alquran.cloud/v1/quran/quran-uthmani');

            if (!$response->successful()) {
                $this->command->error('❌ فشل الاتصال بـ API');
                return;
            }

            $data = $response->json();
            $surahs = $data['data']['surahs'];

            $totalVerses = 0;
            $verses = [];

            foreach ($surahs as $surah) {
                foreach ($surah['ayahs'] as $ayah) {
                    $verses[] = [
                        'surah_id' => $surah['number'],
                        'verse_number' => $ayah['numberInSurah'],
                        'verse_text' => $ayah['text'],
                        'verse_text_simple' => $this->simplifyArabicText($ayah['text']),
                        'page_number' => $ayah['page'],
                        'juz_number' => $ayah['juz'],
                        'hizb_number' => $ayah['hizbQuarter'] ?? null,
                        'sajda' => isset($ayah['sajda']) && $ayah['sajda'] !== false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $totalVerses++;

                    // إدراج دفعة كل 500 آية للأداء
                    if (count($verses) >= 500) {
                        DB::table('verses')->insert($verses);
                        $verses = [];
                        $this->command->info("   تم إدراج {$totalVerses} آية...");
                    }
                }
            }

            // إدراج الآيات المتبقية
            if (count($verses) > 0) {
                DB::table('verses')->insert($verses);
            }

            $this->command->info("✅ تم استيراد {$totalVerses} آية");

        } catch (\Exception $e) {
            $this->command->error('❌ خطأ في استيراد الآيات: ' . $e->getMessage());
        }
    }

    /**
     * تبسيط النص العربي (إزالة التشكيل) للبحث
     */
    private function simplifyArabicText(string $text): string
    {
        // إزالة التشكيل والعلامات الإضافية
        $diacritics = [
            'َ', // فتحة
            'ً', // تنوين فتح
            'ُ', // ضمة
            'ٌ', // تنوين ضم
            'ِ', // كسرة
            'ٍ', // تنوين كسر
            'ّ', // شدة
            'ْ', // سكون
            'ـ', // تطويل
        ];

        return str_replace($diacritics, '', $text);
    }

    /**
     * إنشاء بيانات الصفحات (604 صفحة)
     */
    private function generatePages(): void
    {
        $this->command->info('📄 جاري إنشاء بيانات الصفحات...');

        $pages = [];

        for ($pageNum = 1; $pageNum <= 604; $pageNum++) {
            // الحصول على أول وآخر آية في الصفحة
            $firstVerse = DB::table('verses')
                ->where('page_number', $pageNum)
                ->orderBy('surah_id')
                ->orderBy('verse_number')
                ->first(['surah_id', 'verse_number', 'juz_number']);

            $lastVerse = DB::table('verses')
                ->where('page_number', $pageNum)
                ->orderBy('surah_id', 'desc')
                ->orderBy('verse_number', 'desc')
                ->first(['surah_id', 'verse_number']);

            if ($firstVerse && $lastVerse) {
                $pages[] = [
                    'id' => $pageNum,
                    'juz_number' => $firstVerse->juz_number,
                    'first_surah_id' => $firstVerse->surah_id,
                    'first_verse_number' => $firstVerse->verse_number,
                    'last_surah_id' => $lastVerse->surah_id,
                    'last_verse_number' => $lastVerse->verse_number,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if ($pageNum % 100 == 0) {
                $this->command->info("   تم معالجة {$pageNum} صفحة...");
            }
        }

        DB::table('quran_pages')->insert($pages);
        $this->command->info('✅ تم إنشاء بيانات 604 صفحة');
    }

    /**
     * إنشاء بيانات الأجزاء (30 جزء)
     */
    private function generateJuzs(): void
    {
        $this->command->info('📚 جاري إنشاء بيانات الأجزاء...');

        $juzNames = [
            1 => 'الجزء الأول', 2 => 'الجزء الثاني', 3 => 'الجزء الثالث',
            4 => 'الجزء الرابع', 5 => 'الجزء الخامس', 6 => 'الجزء السادس',
            7 => 'الجزء السابع', 8 => 'الجزء الثامن', 9 => 'الجزء التاسع',
            10 => 'الجزء العاشر', 11 => 'الجزء الحادي عشر', 12 => 'الجزء الثاني عشر',
            13 => 'الجزء الثالث عشر', 14 => 'الجزء الرابع عشر', 15 => 'الجزء الخامس عشر',
            16 => 'الجزء السادس عشر', 17 => 'الجزء السابع عشر', 18 => 'الجزء الثامن عشر',
            19 => 'الجزء التاسع عشر', 20 => 'الجزء العشرون', 21 => 'الجزء الحادي والعشرون',
            22 => 'الجزء الثاني والعشرون', 23 => 'الجزء الثالث والعشرون', 24 => 'الجزء الرابع والعشرون',
            25 => 'الجزء الخامس والعشرون', 26 => 'الجزء السادس والعشرون', 27 => 'الجزء السابع والعشرون',
            28 => 'الجزء الثامن والعشرون', 29 => 'الجزء التاسع والعشرون', 30 => 'الجزء الثلاثون',
        ];

        $juzs = [];

        for ($juzNum = 1; $juzNum <= 30; $juzNum++) {
            $firstVerse = DB::table('verses')
                ->where('juz_number', $juzNum)
                ->orderBy('surah_id')
                ->orderBy('verse_number')
                ->first(['surah_id', 'verse_number']);

            $lastVerse = DB::table('verses')
                ->where('juz_number', $juzNum)
                ->orderBy('surah_id', 'desc')
                ->orderBy('verse_number', 'desc')
                ->first(['surah_id', 'verse_number']);

            if ($firstVerse && $lastVerse) {
                $juzs[] = [
                    'id' => $juzNum,
                    'name_arabic' => $juzNames[$juzNum],
                    'start_surah_id' => $firstVerse->surah_id,
                    'start_verse_number' => $firstVerse->verse_number,
                    'end_surah_id' => $lastVerse->surah_id,
                    'end_verse_number' => $lastVerse->verse_number,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('juzs')->insert($juzs);
        $this->command->info('✅ تم إنشاء بيانات 30 جزءاً');
    }

    /**
     * تحديث جدول السور بمعلومات الصفحات والأجزاء
     */
    private function updateSurahs(): void
    {
        $this->command->info('🔄 جاري تحديث جدول السور...');

        $surahs = DB::table('surahs')->get();

        foreach ($surahs as $surah) {
            $firstVerse = DB::table('verses')
                ->where('surah_id', $surah->id)
                ->orderBy('verse_number')
                ->first(['page_number', 'juz_number']);

            $lastVerse = DB::table('verses')
                ->where('surah_id', $surah->id)
                ->orderBy('verse_number', 'desc')
                ->first(['page_number']);

            if ($firstVerse && $lastVerse) {
                DB::table('surahs')
                    ->where('id', $surah->id)
                    ->update([
                        'start_page' => $firstVerse->page_number,
                        'end_page' => $lastVerse->page_number,
                        'juz_start' => $firstVerse->juz_number,
                    ]);
            }
        }

        $this->command->info('✅ تم تحديث جدول السور');
    }
}
