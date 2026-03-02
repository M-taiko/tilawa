<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // سجّل فقط GET requests ناجحة
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            try {
                $ip = $request->ip();
                $ua = $request->userAgent() ?? '';

                // جلب معلومات الدولة من ip-api.com (مجاني، بدون API key)
                [$countryCode, $countryName, $city] = $this->getGeoInfo($ip);

                VisitorLog::create([
                    'ip'           => $ip,
                    'country_code' => $countryCode,
                    'country_name' => $countryName,
                    'city'         => $city,
                    'page'         => $request->path(),
                    'user_agent'   => substr($ua, 0, 500),
                    'device_type'  => VisitorLog::detectDevice($ua),
                    'visited_at'   => now(),
                ]);
            } catch (\Throwable) {
                // لا توقف التطبيق لو فيه خطأ في التتبع
            }
        }

        return $response;
    }

    private function getGeoInfo(string $ip): array
    {
        // تجاهل IPs محلية
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return ['LOCAL', 'محلي', '-'];
        }

        $cacheKey = 'geo_' . md5($ip);
        $cached = cache()->get($cacheKey);
        if ($cached) return $cached;

        try {
            $ctx = stream_context_create(['http' => ['timeout' => 2]]);
            $json = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode,country,city&lang=ar", false, $ctx);
            if ($json) {
                $data = json_decode($json, true);
                $result = [
                    $data['countryCode'] ?? '??',
                    $data['country']     ?? 'غير معروف',
                    $data['city']        ?? '-',
                ];
                cache()->put($cacheKey, $result, now()->addHours(24));
                return $result;
            }
        } catch (\Throwable) {}

        return ['??', 'غير معروف', '-'];
    }
}
