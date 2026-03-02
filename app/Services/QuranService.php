<?php

namespace App\Services;

use App\Models\Verse;
use App\Models\QuranPage;
use App\Models\Juz;
use App\Models\Surah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

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
                return Surah::orderBy('id')->get();
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
                return Juz::with([
                    'startSurah:id,name_arabic',
                    'endSurah:id,name_arabic'
                ])->orderBy('id')->get();
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
