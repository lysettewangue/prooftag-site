<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContactRequestStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * @property ContactRequestStatus $status
 */
class ContactRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'locale',
        'status',
        'ip',
        'user_agent',
        'notified_at',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => ContactRequestStatus::New->value,
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'status' => ContactRequestStatus::class,
            'notified_at' => 'datetime',
        ];
    }

    public function canTransitionTo(ContactRequestStatus $to): bool
    {
        return $this->status->canTransitionTo($to);
    }

    public function dispatchesInboundMail(): bool
    {
        return $this->status->dispatchesInboundMail();
    }
}
