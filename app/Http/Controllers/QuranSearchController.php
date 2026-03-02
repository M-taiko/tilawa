<?php

namespace App\Http\Controllers;

use App\Models\Surah;
use App\Services\QuranSearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuranSearchController extends Controller
{
    /**
     * Constructor
     */
    public function __construct(protected QuranSearchService $searchService)
    {
    }

    /**
     * صفحة البحث الرئيسية
     */
    public function index(): View
    {
        $surahs = Surah::orderBy('id')->get();

        return view('quran.search.index', compact('surahs'));
    }

    /**
     * تنفيذ عملية البحث
     */
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'q' => 'required|string|min:3|max:255',
            'surah_id' => 'nullable|integer|exists:surahs,id',
            'juz_number' => 'nullable|integer|min:1|max:30',
            'page_number' => 'nullable|integer|min:1|max:604',
            'sajda' => 'nullable|boolean',
        ]);

        $query = $validated['q'];
        $filters = $request->only(['surah_id', 'juz_number', 'page_number', 'sajda']);

        $results = $this->searchService->search($query, $filters, 20);
        $surahs = Surah::orderBy('id')->get();

        return view('quran.search.results', compact('results', 'query', 'surahs', 'filters'));
    }

    /**
     * API: البحث (JSON)
     */
    public function apiSearch(Request $request)
    {
        $validated = $request->validate([
            'q' => 'required|string|min:3|max:255',
            'surah_id' => 'nullable|integer|exists:surahs,id',
            'juz_number' => 'nullable|integer|min:1|max:30',
            'page_number' => 'nullable|integer|min:1|max:604',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = $validated['q'];
        $filters = $request->only(['surah_id', 'juz_number', 'page_number']);
        $perPage = $validated['per_page'] ?? 20;

        $results = $this->searchService->search($query, $filters, $perPage);

        return response()->json([
            'query' => $query,
            'filters' => $filters,
            'results' => $results,
        ]);
    }

    /**
     * API: الحصول على اقتراحات البحث (JSON)
     */
    public function apiSuggestions(Request $request)
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:100',
            'limit' => 'nullable|integer|min:1|max:10',
        ]);

        $query = $validated['q'];
        $limit = $validated['limit'] ?? 5;

        $suggestions = $this->searchService->getSuggestions($query, $limit);

        return response()->json(['suggestions' => $suggestions]);
    }
}
