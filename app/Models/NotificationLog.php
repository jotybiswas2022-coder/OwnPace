<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Dedupe ledger for automated (system-triggered) notifications — see the
 * notification_logs migration for the rationale.
 */
class NotificationLog extends Model
{
    protected $fillable = [
        'user_id', 'type', 'entity_type', 'entity_id',
        'channels', 'sent_at',
    ];

    protected $casts = [
        'channels' => 'array',
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Has this automated notification already been dispatched for this
     * entity (e.g. this exact installment payment)?
     */
    public static function alreadySent(string $type, string $entityType, int $entityId): bool
    {
        return self::where('type', $type)
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->exists();
    }
}
