<?php

namespace App\Services;

use App\Models\Verse;
use App\Models\QuranPage;
use App\Models\Juz;
use App\Models\Surah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class QuranService
{
    /**
     * Cache duration in minutes (30 days)
     */
    const CACHE_DURATION = 60 * 24 * 30;

    /**
     * الحصول على آيات صفحة معينة (مع كاش)
     */
    public function getPageVerses(int $pageNumber): Collection
    {
        return Cache::remember(
            "quran:page:{$pageNumber}:verses",
            now()->addMinutes(self::CACHE_DURATION),
            function () use ($pageNumber) {
                return Verse::where('page_number', $pageNumber)
                    ->with('surah:id,name_arabic,name_english')
                    ->orderBy('surah_id')
                    ->orderBy('verse_number')
                    ->get();
            }
        );
    }

    /**
     * الحصول على معلومات صفحة
     */
    public function getPageInfo(int $pageNumber): ?QuranPage
    {
        return Cache::remember(
            "quran:page:{$pageNumber}:info",
            now()->addMinutes(self::CACHE_DURATION),
            function () use ($pageNumber) {
                return QuranPage::with([
                    'firstSurah:id,name_arabic,name_english',
                    'lastSurah:id,name_arabic,name_english',
                    'juz:id,name_arabic'
                ])->find($pageNumber);
            }
        );
    }

    /**
     * الحصول على آيات جزء معين
     */
    public function getJuzVerses(int $juzNumber): Collection
    {
        return Cache::remember(
            "quran:juz:{$juzNumber}:verses",
            now()->addMinutes(self::CACHE_DURATION),
            function () use ($juzNumber) {
                return Verse::where('juz_number', $juzNumber)
                    ->with('surah:id,name_arabic,name_english')
                    ->orderBy('surah_id')
                    ->orderBy('verse_number')
                    ->get();
            }
        );
    }

    /**
     * الحصول على معلومات جزء
     */
    public function getJuzInfo(int $juzNumber): ?Juz
    {
        return Cache::remember(
            "quran:juz:{$juzNumber}:info",
            now()->addMinutes(self::CACHE_DURATION),
            function () use ($juzNumber) {
                return Juz::with([
                    'startSurah:id,name_arabic,name_english',
                    'endSurah:id,name_arabic,name_english',
                ])->find($juzNumber);
            }
        );
    }

    /**
     * الحصول على آيات سورة معينة
     */
    public function getSurahVerses(int $surahId): Collection
    {
        return Cache::remember(
            "quran:surah:{$surahId}:verses",
            now()->addMinutes(self::CACHE_DURATION),
            function () use ($surahId) {
                return Verse::where('surah_id', $surahId)
                    ->orderBy('verse_number')
                    ->get();
            }
        );
    }

    /**
     * الحصول على جميع السور
     */
    public function getAllSurahs(): Collection
    {
        return Cache::remember(
            'quran:surahs:all',
            now()->addMinutes(self::CACHE_DURATION),
            function () {
                $surahs = Surah::orderBy('id')->get();

                // لو start_page مش محسوب (الـ seeder لم يتشغل) نحسبه من جدول الآيات
                $needsUpdate = $surahs->where('start_page', null)->isNotEmpty();
                if ($needsUpdate) {
                    $firstPages = Verse::select('surah_id', DB::raw('MIN(page_number) as start_page'))
                        ->groupBy('surah_id')
                        ->pluck('start_page', 'surah_id');

                    $surahs->each(function ($surah) use ($firstPages) {
                        if (!$surah->start_page && isset($firstPages[$surah->id])) {
                            $surah->start_page = $firstPages[$surah->id];
                        }
                    });
                }

                return $surahs;
            }
        );
    }

    /**
     * الحصول على جميع الأجزاء
     */
    public function getAllJuzs(): Collection
    {
        return Cache::remember(
            'quran:juzs:all',
            now()->addMinutes(self::CACHE_DURATION),
            function () {
                $juzs = Juz::with([
                    'startSurah:id,name_arabic,start_page',
                    'endSurah:id,name_arabic'
                ])->orderBy('id')->get();

                // لو جدول juzs فاضي — نولّده من جدول الآيات
                if ($juzs->isEmpty()) {
                    $juzNames = [
                        1=>'الجزء الأول',2=>'الجزء الثاني',3=>'الجزء الثالث',
                        4=>'الجزء الرابع',5=>'الجزء الخامس',6=>'الجزء السادس',
                        7=>'الجزء السابع',8=>'الجزء الثامن',9=>'الجزء التاسع',
                        10=>'الجزء العاشر',11=>'الجزء الحادي عشر',12=>'الجزء الثاني عشر',
                        13=>'الجزء الثالث عشر',14=>'الجزء الرابع عشر',15=>'الجزء الخامس عشر',
                        16=>'الجزء السادس عشر',17=>'الجزء السابع عشر',18=>'الجزء الثامن عشر',
                        19=>'الجزء التاسع عشر',20=>'الجزء العشرون',21=>'الجزء الحادي والعشرون',
                        22=>'الجزء الثاني والعشرون',23=>'الجزء الثالث والعشرون',24=>'الجزء الرابع والعشرون',
                        25=>'الجزء الخامس والعشرون',26=>'الجزء السادس والعشرون',27=>'الجزء السابع والعشرون',
                        28=>'الجزء الثامن والعشرون',29=>'الجزء التاسع والعشرون',30=>'الجزء الثلاثون',
                    ];

                    // أول سورة وأول صفحة لكل جزء من جدول الآيات
                    $firstVerses = Verse::select('juz_number',
                            DB::raw('MIN(surah_id) as surah_id'),
                            DB::raw('MIN(page_number) as start_page'))
                        ->groupBy('juz_number')
                        ->orderBy('juz_number')
                        ->get()
                        ->keyBy('juz_number');

                    $surahIds = $firstVerses->pluck('surah_id')->unique();
                    $surahsMap = Surah::whereIn('id', $surahIds)->get()->keyBy('id');

                    $juzs = collect();
                    for ($i = 1; $i <= 30; $i++) {
                        $v = $firstVerses[$i] ?? null;
                        $surah = $v ? ($surahsMap[$v->surah_id] ?? null) : null;
                        if ($surah && $v) {
                            $surah->start_page = $v->start_page;
                        }
                        $juz = new Juz();
                        $juz->id = $i;
                        $juz->name_arabic = $juzNames[$i] ?? "الجزء {$i}";
                        $juz->start_surah_id = $v?->surah_id;
                        $juz->setRelation('startSurah', $surah);
                        $juzs->push($juz);
                    }
                }

                return $juzs;
            }
        );
    }

    /**
     * الحصول على كل آيات القرآن دفعة واحدة (للتحميل offline)
     */
    public function getAllVerses(): Collection
    {
        return Cache::remember(
            'quran:all:verses',
            now()->addMinutes(self::CACHE_DURATION),
            function () {
                return Verse::select('id', 'surah_id', 'verse_number', 'verse_text', 'page_number', 'juz_number', 'sajda')
                    ->orderBy('surah_id')
                    ->orderBy('verse_number')
                    ->get();
            }
        );
    }

    /**
     * الحصول على معلومات كل الصفحات (للتحميل offline)
     */
    public function getAllPages(): Collection
    {
        return Cache::remember(
            'quran:all:pages',
            now()->addMinutes(self::CACHE_DURATION),
            function () {
                return QuranPage::select('id', 'juz_number', 'first_surah_id', 'first_verse_number', 'last_surah_id', 'last_verse_number')
                    ->orderBy('id')
                    ->get();
            }
        );
    }

    /**
     * تحديد الصفحة من سورة وآية
     */
    public function getPageNumber(int $surahId, int $verseNumber): ?int
    {
        $verse = Verse::where('surah_id', $surahId)
            ->where('verse_number', $verseNumber)
            ->first(['page_number']);

        return $verse?->page_number;
    }

    /**
     * الحصول على نطاق الآيات بين آيتين
     */
    public function getVerseRange(int $surahId, int $fromVerse, int $toVerse): Collection
    {
        return Verse::where('surah_id', $surahId)
            ->whereBetween('verse_number', [$fromVerse, $toVerse])
            ->orderBy('verse_number')
            ->get();
    }
}
