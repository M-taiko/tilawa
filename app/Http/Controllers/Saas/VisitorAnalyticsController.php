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

        $totalVisits    = VisitorLog::where('visited_at', '>=', $from)->count();
        $uniqueVisitors = VisitorLog::where('visited_at', '>=', $from)->distinct('ip')->count('ip');
        $todayVisits    = VisitorLog::whereDate('visited_at', today())->count();
        $weekVisits     = VisitorLog::where('visited_at', '>=', now()->subDays(7))->count();

        $byCountry = VisitorLog::where('visited_at', '>=', $from)
            ->select('country_name', 'country_code', DB::raw('count(*) as visits'))
            ->groupBy('country_name', 'country_code')
            ->orderByDesc('visits')->limit(20)->get();

        $byDevice = VisitorLog::where('visited_at', '>=', $from)
            ->select('device_type', DB::raw('count(*) as visits'))
            ->groupBy('device_type')->orderByDesc('visits')->get();

        $topPages = VisitorLog::where('visited_at', '>=', $from)
            ->select('page', DB::raw('count(*) as visits'))
            ->groupBy('page')->orderByDesc('visits')->limit(10)->get();

        $dailyVisits = VisitorLog::where('visited_at', '>=', $from)
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('count(*) as visits'))
            ->groupBy('date')->orderBy('date')->get()->keyBy('date');

        $chartLabels = [];
        $chartData   = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');
            $chartData[]   = $dailyVisits[$date]->visits ?? 0;
        }

        // جدول الزوار الفريدين مجمّع حسب IP
        $visitors = VisitorLog::where('visited_at', '>=', $from)
            ->select(
                'ip', 'country_name', 'country_code', 'city', 'device_type',
                DB::raw('count(*) as visit_count'),
                DB::raw('MAX(visited_at) as last_visit'),
                DB::raw('MIN(visited_at) as first_visit')
            )
            ->groupBy('ip', 'country_name', 'country_code', 'city', 'device_type')
            ->orderByDesc('last_visit')
            ->paginate(25, ['*'], 'visitors_page');

        return view('saas.analytics.index', compact(
            'totalVisits', 'uniqueVisitors', 'todayVisits', 'weekVisits',
            'byCountry', 'byDevice', 'topPages',
            'chartLabels', 'chartData', 'days', 'visitors'
        ));
    }

    public function visitorDetail(string $ip)
    {
        $summary = VisitorLog::where('ip', $ip)
            ->select(
                'ip', 'country_name', 'country_code', 'city', 'user_agent', 'device_type',
                DB::raw('count(*) as visit_count'),
                DB::raw('MAX(visited_at) as last_visit'),
                DB::raw('MIN(visited_at) as first_visit')
            )
            ->groupBy('ip', 'country_name', 'country_code', 'city', 'user_agent', 'device_type')
            ->first();

        if (!$summary) abort(404);

        $pages = VisitorLog::where('ip', $ip)
            ->select('page', DB::raw('count(*) as visits'))
            ->groupBy('page')->orderByDesc('visits')->get();

        $logs = VisitorLog::where('ip', $ip)
            ->orderByDesc('visited_at')
            ->paginate(30);

        return view('saas.analytics.visitor', compact('summary', 'logs', 'pages', 'ip'));
    }
}
