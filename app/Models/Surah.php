<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surah extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'name_arabic',
        'ayah_count',
        'start_page',
    ];

    protected $appends = ['name_ar'];

    public function getNameArAttribute()
    {
        return $this->name_arabic;
    }
}
