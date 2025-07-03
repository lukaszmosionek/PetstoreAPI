<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Available = 'available';
    case Pending = 'pending';
    case Sold = 'sold';

    /**
     * Get all available statuses as an array of strings.
     *
     * @return array<string>
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
