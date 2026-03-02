<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Juz extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juzs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name_arabic',
        'start_surah_id',
        'start_verse_number',
        'end_surah_id',
        'end_verse_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'start_surah_id' => 'integer',
        'start_verse_number' => 'integer',
        'end_surah_id' => 'integer',
        'end_verse_number' => 'integer',
    ];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get all verses in this juz.
     */
    public function verses(): HasMany
    {
        return $this->hasMany(Verse::class, 'juz_number', 'id')
            ->orderBy('surah_id')
            ->orderBy('verse_number');
    }

    /**
     * Get all pages in this juz.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(QuranPage::class, 'juz_number', 'id')
            ->orderBy('id');
    }

    /**
     * Get the starting surah of this juz.
     */
    public function startSurah(): BelongsTo
    {
        return $this->belongsTo(Surah::class, 'start_surah_id');
    }

    /**
     * Get the ending surah of this juz.
     */
    public function endSurah(): BelongsTo
    {
        return $this->belongsTo(Surah::class, 'end_surah_id');
    }

    /**
     * Get the total number of verses in this juz.
     */
    public function getTotalVersesAttribute(): int
    {
        return $this->verses()->count();
    }

    /**
     * Get the total number of pages in this juz.
     */
    public function getTotalPagesAttribute(): int
    {
        return $this->pages()->count();
    }

    /**
     * Get the next juz.
     */
    public function next(): ?Juz
    {
        if ($this->id >= 30) {
            return null;
        }

        return static::find($this->id + 1);
    }

    /**
     * Get the previous juz.
     */
    public function previous(): ?Juz
    {
        if ($this->id <= 1) {
            return null;
        }

        return static::find($this->id - 1);
    }
}
