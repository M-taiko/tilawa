<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ip', 'country_code', 'country_name', 'city',
        'page', 'user_agent', 'device_type', 'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public static function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) return 'tablet';
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) return 'mobile';
        return 'desktop';
    }
}
