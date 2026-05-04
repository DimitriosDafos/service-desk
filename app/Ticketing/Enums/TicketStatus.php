<?php

namespace App\Ticketing\Enums;

enum TicketStatus: string
{
    case NEW = 'new';
    case TRIAGED = 'triaged';
    case IN_PROGRESS = 'in_progress';
    case PENDING = 'pending';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::NEW => 'New',
            self::TRIAGED => 'Triaged',
            self::IN_PROGRESS => 'In Progress',
            self::PENDING => 'Pending',
            self::RESOLVED => 'Resolved',
            self::CLOSED => 'Closed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NEW => 'blue',
            self::TRIAGED => 'yellow',
            self::IN_PROGRESS => 'orange',
            self::PENDING => 'gray',
            self::RESOLVED => 'green',
            self::CLOSED => 'gray',
            self::CANCELLED => 'red',
        };
    }

    public function isOpen(): bool
    {
        return in_array($this, [self::NEW, self::TRIAGED, self::IN_PROGRESS, self::PENDING]);
    }

    public function isClosed(): bool
    {
        return in_array($this, [self::RESOLVED, self::CLOSED, self::CANCELLED]);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
