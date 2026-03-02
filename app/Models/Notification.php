<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use TenantScoped;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * تحديد الإشعار كمقروء
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * التحقق من قراءة الإشعار
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Scope للإشعارات غير المقروءة
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope للإشعارات المقروءة
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}
