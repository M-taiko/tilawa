<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $days = (int) $request->get('days', 30);
        $days = in_array($days, [7, 30, 90, 365]) ? $days : 30;
        $from = now()->subDays($days);

        // إجمالي الزيارات
        $totalVisits   = VisitorLog::where('visited_at', '>=', $from)->count();
        $uniqueVisitors = VisitorLog::where('visited_at', '>=', $from)->distinct('ip')->count('ip');

        // زيارات اليوم
        $todayVisits = VisitorLog::whereDate('visited_at', today())->count();

        // زيارات الأسبوع
        $weekVisits = VisitorLog::where('visited_at', '>=', now()->subDays(7))->count();

        // توزيع الدول
        $byCountry = VisitorLog::where('visited_at', '>=', $from)
            ->select('country_name', 'country_code', DB::raw('count(*) as visits'))
            ->groupBy('country_name', 'country_code')
            ->orderByDesc('visits')
            ->limit(20)
            ->get();

        // توزيع الأجهزة
        $byDevice = VisitorLog::where('visited_at', '>=', $from)
            ->select('device_type', DB::raw('count(*) as visits'))
            ->groupBy('device_type')
            ->orderByDesc('visits')
            ->get();

        // الصفحات الأكثر زيارة
        $topPages = VisitorLog::where('visited_at', '>=', $from)
            ->select('page', DB::raw('count(*) as visits'))
            ->groupBy('page')
            ->orderByDesc('visits')
            ->limit(10)
            ->get();

        // الزيارات اليومية (للرسم البياني)
        $dailyVisits = VisitorLog::where('visited_at', '>=', $from)
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('count(*) as visits'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // ملء الأيام الفارغة
        $chartLabels = [];
        $chartData   = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');
            $chartData[]   = $dailyVisits[$date]->visits ?? 0;
        }

        return view('saas.analytics.index', compact(
            'totalVisits', 'uniqueVisitors', 'todayVisits', 'weekVisits',
            'byCountry', 'byDevice', 'topPages',
            'chartLabels', 'chartData', 'days'
        ));
    }
}
