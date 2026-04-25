<?php

namespace App\Http\Controllers;

use App\Models\Surah;
use App\Models\Student;
use App\Services\QuranService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuranController extends Controller
{
    /**
     * Constructor
     */
    public function __construct(protected QuranService $quranService)
    {
    }

    /**
     * الصفحة الرئيسية للمصحف
     */
    public function index(): View
    {
        $surahs = $this->quranService->getAllSurahs();
        $juzs = $this->quranService->getAllJuzs();

        return view('quran.index', compact('surahs', 'juzs'));
    }

    /**
     * عرض صفحة من المصحف
     */
    public function showPage(int $pageNumber, Request $request): View
    {
        // التحقق من رقم الصفحة
        if ($pageNumber < 1 || $pageNumber > 604) {
            abort(404, 'الصفحة غير موجودة');
        }

        // Check if PDF pages are available
        $pdfPagePath = public_path('quran-pages/page-' . str_pad($pageNumber, 3, '0', STR_PAD_LEFT) . '.jpg');
        $usePdfView = file_exists($pdfPagePath);

        $verses = $this->quranService->getPageVerses($pageNumber);
        $page = $this->quranService->getPageInfo($pageNumber);

        // If PDF pages available, use the PDF view
        if ($usePdfView) {
            return view('quran.page-with-pdf', compact('verses', 'pageNumber'));
        }

        // البسملة الفعلية من قاعدة البيانات (سورة 2 آية 1 تبدأ بها)
        static $basmalaPrefix = null;
        if ($basmalaPrefix === null) {
            $s2v1 = \App\Models\Verse::where('surah_id', 2)->where('verse_number', 1)->value('verse_text');
            $s6v1 = \App\Models\Verse::where('surah_id', 6)->where('verse_number', 1)->value('verse_text');
            if ($s2v1 && $s6v1) {
                $len = min(strlen($s2v1), strlen($s6v1));
                $commonLen = 0;
                for ($i = 0; $i < $len; $i++) {
                    if ($s2v1[$i] === $s6v1[$i]) $commonLen++;
                    else break;
                }
                $basmalaPrefix = substr($s2v1, 0, $commonLen);
            }
            $basmalaPrefix = $basmalaPrefix ?: '';
        }

        // معلومات الـ highlighting للطالب
        $highlightInfo = [
            'student_id' => $request->query('student_id'),
            'highlight_start' => $request->query('highlight_start'),
            'highlight_end' => $request->query('highlight_end'),
            'mode' => $request->query('mode', 'student'),
        ];

        $student = null;
        if ($highlightInfo['student_id']) {
            $student = Student::find($highlightInfo['student_id']);
        }

        return view('quran.page', compact('verses', 'page', 'pageNumber', 'highlightInfo', 'student', 'basmalaPrefix'));
    }

    /**
     * عرض سورة كاملة
     */
    public function showSurah(int $surahId): View
    {
        $surah = Surah::findOrFail($surahId);
        $verses = $this->quranService->getSurahVerses($surahId);

        return view('quran.surah', compact('surah', 'verses'));
    }

    /**
     * عرض جزء كامل
     */
    public function showJuz(int $juzNumber): View
    {
        // التحقق من رقم الجزء
        if ($juzNumber < 1 || $juzNumber > 30) {
            abort(404, 'الجزء غير موجود');
        }

        $juz = $this->quranService->getJuzInfo($juzNumber);
        $verses = $this->quranService->getJuzVerses($juzNumber);

        return view('quran.juz', compact('juz', 'verses', 'juzNumber'));
    }

    /**
     * API: الحصول على آيات صفحة (JSON)
     */
    public function apiGetPage(int $pageNumber)
    {
        if ($pageNumber < 1 || $pageNumber > 604) {
            return response()->json(['error' => 'رقم الصفحة غير صحيح'], 404);
        }

        $verses = $this->quranService->getPageVerses($pageNumber);
        $page = $this->quranService->getPageInfo($pageNumber);

        return response()->json([
            'page_number' => $pageNumber,
            'page_info' => $page,
            'verses' => $verses,
        ]);
    }

    /**
     * API: الحصول على آية محددة (JSON)
     */
    public function apiGetVerse(int $surahId, int $verseNumber)
    {
        $verse = $this->quranService->getVerse($surahId, $verseNumber);

        if (!$verse) {
            return response()->json(['error' => 'الآية غير موجودة'], 404);
        }

        return response()->json(['verse' => $verse]);
    }

    /**
     * API: تحميل كل القرآن للعمل offline (Dexie)
     */
    public function sitemap()
    {
        $surahs = $this->quranService->getAllSurahs();
        return response()
            ->view('sitemap', compact('surahs'))
            ->header('Content-Type', 'application/xml');
    }

    public function apiDownloadAll()
    {
        $verses = $this->quranService->getAllVerses();
        $pages  = $this->quranService->getAllPages();
        $surahs = $this->quranService->getAllSurahs()
            ->map(fn($s) => [
                'id'          => $s->id,
                'name_arabic' => $s->name_arabic,
                'ayah_count'  => $s->ayah_count,
                'start_page'  => $s->start_page,
            ]);

        return response()->json([
            'version' => 1,
            'surahs'  => $surahs,
            'pages'   => $pages,
            'verses'  => $verses,
        ])->header('Cache-Control', 'public, max-age=2592000');
    }
}
