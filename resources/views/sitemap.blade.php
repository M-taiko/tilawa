<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- صفحة المصحف الرئيسية --}}
    <url>
        <loc>{{ url('/quran') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- صفحات المصحف 1-604 --}}
    @for($i = 1; $i <= 604; $i++)
    <url>
        <loc>{{ url('/quran/page/' . $i) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endfor

    {{-- السور 1-114 --}}
    @foreach($surahs as $surah)
    <url>
        <loc>{{ url('/quran/surah/' . $surah->id) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    {{-- الأجزاء 1-30 --}}
    @for($i = 1; $i <= 30; $i++)
    <url>
        <loc>{{ url('/quran/juz/' . $i) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endfor

</urlset>
