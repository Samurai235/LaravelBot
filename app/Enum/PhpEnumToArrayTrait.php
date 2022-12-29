<?php

declare(strict_types=1);

namespace App\Enum;

trait PhpEnumToArrayTrait
{
    /**
     * @return array<array-key, string>
     */
    public static function toArray(): array
    {
        return array_column(self::toArray(), 'value');
    }
}
