<?php

declare(strict_types=1);

namespace App\Enum;

final class OptionsFood
{
    public const FOOD = 'food';
    public const FOOD1 = 'food1';
    public const FOOD_STAKE = 'stake';
    public const FOOD_BUZ = 'buz';

    public static function toArray(): array
    {
        $newRef = new \ReflectionClass(self::class);
        return $newRef->getConstants(\ReflectionClassConstant::IS_PUBLIC);

    }
}
