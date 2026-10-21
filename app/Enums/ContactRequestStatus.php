<?php

declare(strict_types=1);

namespace App\Enums;

enum ContactRequestStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Responded = 'responded';
    case Closed = 'closed';
    case Spam = 'spam';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return list<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::New => [self::InProgress, self::Spam, self::Closed],
            self::InProgress => [self::Responded, self::Closed, self::Spam],
            self::Responded => [self::Closed, self::InProgress],
            self::Closed => [],
            self::Spam => [self::Closed],
        };
    }

    public function canTransitionTo(self $to): bool
    {
        return in_array($to, $this->allowedTransitions(), true);
    }

    public function dispatchesInboundMail(): bool
    {
        return $this !== self::Spam;
    }
}
