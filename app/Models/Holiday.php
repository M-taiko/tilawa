<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use SoftDeletes, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'type', // holiday, vacation, special_event
        'is_recurring',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: Active holidays
     */
    public function scopeActive($query)
    {
        return $query->where('end_date', '>=', now()->toDateString());
    }

    /**
     * Scope: Upcoming holidays
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date');
    }

    /**
     * Check if a date falls within this holiday
     */
    public function includesDate(string $date): bool
    {
        return $date >= $this->start_date->toDateString() &&
               $date <= $this->end_date->toDateString();
    }
}
