<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'surah_id',
        'verse_number',
        'verse_text',
        'verse_text_simple',
        'page_number',
        'juz_number',
        'hizb_number',
        'sajda',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sajda' => 'boolean',
        'page_number' => 'integer',
        'juz_number' => 'integer',
        'hizb_number' => 'integer',
        'verse_number' => 'integer',
    ];

    /**
     * Get the surah that owns the verse.
     */
    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class);
    }

    /**
     * Get the Quran page this verse belongs to.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(QuranPage::class, 'page_number', 'id');
    }

    /**
     * Get the juz this verse belongs to.
     */
    public function juz(): BelongsTo
    {
        return $this->belongsTo(Juz::class, 'juz_number', 'id');
    }

    /**
     * Scope a query to search verses by text.
     */
    public function scopeSearch($query, string $term)
    {
        return $query->whereRaw(
            "MATCH(verse_text) AGAINST(? IN BOOLEAN MODE)",
            [$term]
        );
    }

    /**
     * Scope a query to filter by surah.
     */
    public function scopeBySurah($query, int $surahId)
    {
        return $query->where('surah_id', $surahId);
    }

    /**
     * Scope a query to filter by page.
     */
    public function scopeByPage($query, int $pageNumber)
    {
        return $query->where('page_number', $pageNumber);
    }

    /**
     * Scope a query to filter by juz.
     */
    public function scopeByJuz($query, int $juzNumber)
    {
        return $query->where('juz_number', $juzNumber);
    }

    /**
     * Scope a query to get verses with sajda.
     */
    public function scopeWithSajda($query)
    {
        return $query->where('sajda', true);
    }

    /**
     * Get the verse reference (e.g., "البقرة:255")
     */
    public function getReferenceAttribute(): string
    {
        return $this->surah->name_arabic . ':' . $this->verse_number;
    }

    /**
     * Get the next verse.
     */
    public function next(): ?Verse
    {
        // Try to get next verse in same surah
        $next = static::where('surah_id', $this->surah_id)
            ->where('verse_number', '>', $this->verse_number)
            ->orderBy('verse_number')
            ->first();

        // If end of surah, get first verse of next surah
        if (!$next && $this->surah_id < 114) {
            $next = static::where('surah_id', $this->surah_id + 1)
                ->where('verse_number', 1)
                ->first();
        }

        return $next;
    }

    /**
     * Get the previous verse.
     */
    public function previous(): ?Verse
    {
        // Try to get previous verse in same surah
        $previous = static::where('surah_id', $this->surah_id)
            ->where('verse_number', '<', $this->verse_number)
            ->orderBy('verse_number', 'desc')
            ->first();

        // If start of surah, get last verse of previous surah
        if (!$previous && $this->surah_id > 1) {
            $previous = static::where('surah_id', $this->surah_id - 1)
                ->orderBy('verse_number', 'desc')
                ->first();
        }

        return $previous;
    }
}
