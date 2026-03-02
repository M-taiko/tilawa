<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuranPage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quran_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'juz_number',
        'first_surah_id',
        'first_verse_number',
        'last_surah_id',
        'last_verse_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'juz_number' => 'integer',
        'first_surah_id' => 'integer',
        'first_verse_number' => 'integer',
        'last_surah_id' => 'integer',
        'last_verse_number' => 'integer',
    ];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get all verses on this page.
     */
    public function verses(): HasMany
    {
        return $this->hasMany(Verse::class, 'page_number', 'id')
            ->orderBy('surah_id')
            ->orderBy('verse_number');
    }

    /**
     * Get the juz this page belongs to.
     */
    public function juz(): BelongsTo
    {
        return $this->belongsTo(Juz::class, 'juz_number', 'id');
    }

    /**
     * Get the first surah on this page.
     */
    public function firstSurah(): BelongsTo
    {
        return $this->belongsTo(Surah::class, 'first_surah_id');
    }

    /**
     * Get the last surah on this page.
     */
    public function lastSurah(): BelongsTo
    {
        return $this->belongsTo(Surah::class, 'last_surah_id');
    }

    /**
     * Get the page reference (e.g., "صفحة 1")
     */
    public function getReferenceAttribute(): string
    {
        return "صفحة {$this->id}";
    }

    /**
     * Get the next page.
     */
    public function next(): ?QuranPage
    {
        if ($this->id >= 604) {
            return null;
        }

        return static::find($this->id + 1);
    }

    /**
     * Get the previous page.
     */
    public function previous(): ?QuranPage
    {
        if ($this->id <= 1) {
            return null;
        }

        return static::find($this->id - 1);
    }

    /**
     * Check if this page starts a new surah.
     */
    public function startsNewSurah(): bool
    {
        return $this->first_verse_number === 1;
    }

    /**
     * Get the surah that starts on this page (if any).
     */
    public function getStartingSurahAttribute(): ?Surah
    {
        if ($this->startsNewSurah()) {
            return $this->firstSurah;
        }

        return null;
    }
}
