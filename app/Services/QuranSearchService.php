<?php

namespace App\Services;

use App\Models\Verse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuranSearchService
{
    /**
     * البحث في القرآن الكريم
     *
     * @param string $query النص المراد البحث عنه
     * @param array $filters فلاتر إضافية (surah_id, juz_number, page_number)
     * @param int $perPage عدد النتائج في كل صفحة
     * @return LengthAwarePaginator
     */
    public function search(string $query, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $searchQuery = Verse::query();

        // البحث النصي (Fulltext Search)
        if (!empty($query)) {
            // تنظيف النص للبحث
            $cleanQuery = $this->cleanSearchQuery($query);

            // استخدام Fulltext Search
            $searchQuery->search($cleanQuery);
        }

        // فلتر حسب السورة
        if (isset($filters['surah_id']) && !empty($filters['surah_id'])) {
            $searchQuery->where('surah_id', $filters['surah_id']);
        }

        // فلتر حسب الجزء
        if (isset($filters['juz_number']) && !empty($filters['juz_number'])) {
            $searchQuery->where('juz_number', $filters['juz_number']);
        }

        // فلتر حسب الصفحة
        if (isset($filters['page_number']) && !empty($filters['page_number'])) {
            $searchQuery->where('page_number', $filters['page_number']);
        }

        // فلتر آيات السجدة فقط
        if (isset($filters['sajda']) && $filters['sajda']) {
            $searchQuery->where('sajda', true);
        }

        // تحميل العلاقة مع السورة
        $searchQuery->with('surah:id,name_arabic,name_english');

        // ترتيب النتائج (حسب السورة ثم رقم الآية)
        $searchQuery->orderBy('surah_id')->orderBy('verse_number');

        // إرجاع النتائج مع Pagination
        return $searchQuery->paginate($perPage);
    }

    /**
     * البحث البسيط (بدون فلاتر)
     *
     * @param string $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function simpleSearch(string $query, int $perPage = 20): LengthAwarePaginator
    {
        return $this->search($query, [], $perPage);
    }

    /**
     * البحث في سورة معينة
     *
     * @param string $query
     * @param int $surahId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchInSurah(string $query, int $surahId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->search($query, ['surah_id' => $surahId], $perPage);
    }

    /**
     * البحث في جزء معين
     *
     * @param string $query
     * @param int $juzNumber
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchInJuz(string $query, int $juzNumber, int $perPage = 20): LengthAwarePaginator
    {
        return $this->search($query, ['juz_number' => $juzNumber], $perPage);
    }

    /**
     * البحث في صفحة معينة
     *
     * @param string $query
     * @param int $pageNumber
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchInPage(string $query, int $pageNumber, int $perPage = 20): LengthAwarePaginator
    {
        return $this->search($query, ['page_number' => $pageNumber], $perPage);
    }

    /**
     * تنظيف نص البحث
     *
     * @param string $query
     * @return string
     */
    private function cleanSearchQuery(string $query): string
    {
        // إزالة المسافات الزائدة
        $query = trim($query);

        // إزالة الأحرف الخاصة (اختياري - حسب الحاجة)
        // يمكن إضافة المزيد من التنظيف هنا إذا لزم الأمر

        return $query;
    }

    /**
     * تظليل الكلمات المطابقة في النص
     *
     * @param string $text النص الأصلي
     * @param string $query الكلمة المبحوث عنها
     * @return string النص مع تظليل
     */
    public function highlightMatches(string $text, string $query): string
    {
        if (empty($query)) {
            return $text;
        }

        // تقسيم الاستعلام إلى كلمات
        $words = explode(' ', $query);

        foreach ($words as $word) {
            $word = trim($word);
            if (empty($word)) {
                continue;
            }

            // البحث عن الكلمة وتظليلها (case-insensitive)
            $pattern = '/' . preg_quote($word, '/') . '/u';
            $text = preg_replace(
                $pattern,
                '<mark class="bg-gold-200 text-gold-900 font-semibold">$0</mark>',
                $text
            );
        }

        return $text;
    }

    /**
     * الحصول على اقتراحات البحث
     *
     * @param string $query
     * @param int $limit
     * @return array
     */
    public function getSuggestions(string $query, int $limit = 5): array
    {
        if (strlen($query) < 3) {
            return [];
        }

        $results = Verse::search($query)
            ->with('surah:id,name_arabic')
            ->limit($limit)
            ->get();

        return $results->map(function ($verse) {
            return [
                'surah' => $verse->surah->name_arabic,
                'verse_number' => $verse->verse_number,
                'preview' => mb_substr($verse->verse_text, 0, 50) . '...',
                'url' => route('quran.page', $verse->page_number),
            ];
        })->toArray();
    }
}
