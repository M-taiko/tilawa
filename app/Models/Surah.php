<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surah extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'name_arabic',
        'name_english',
        'number',
        'ayah_count',
        'start_page',
        'end_page',
        'juz_start',
    ];

    protected $casts = [
        'id' => 'integer',
        'number' => 'integer',
        'ayah_count' => 'integer',
        'start_page' => 'integer',
        'end_page' => 'integer',
        'juz_start' => 'integer',
    ];

    protected $appends = ['name_ar'];

    public function getNameArAttribute()
    {
        return $this->name_arabic;
    }

    /**
     * Get all verses in this surah.
     */
    public function verses(): HasMany
    {
        return $this->hasMany(Verse::class, 'surah_id')
            ->orderBy('verse_number');
    }

    /**
     * Get the juz this surah starts in.
     */
    public function juz(): BelongsTo
    {
        return $this->belongsTo(Juz::class, 'juz_start', 'id');
    }

    /**
     * Get the total number of pages this surah spans.
     */
    public function getTotalPagesAttribute(): ?int
    {
        if ($this->start_page && $this->end_page) {
            return $this->end_page - $this->start_page + 1;
        }

        return null;
    }

    /**
     * Get a specific verse from this surah.
     */
    public function getVerse(int $verseNumber): ?Verse
    {
        return $this->verses()
            ->where('verse_number', $verseNumber)
            ->first();
    }

    /**
     * Get the next surah.
     */
    public function next(): ?Surah
    {
        if ($this->id >= 114) {
            return null;
        }

        return static::find($this->id + 1);
    }

    /**
     * Get the previous surah.
     */
    public function previous(): ?Surah
    {
        if ($this->id <= 1) {
            return null;
        }

        return static::find($this->id - 1);
    }
}
