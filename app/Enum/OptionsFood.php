<?php

declare(strict_types=1);

namespace App\Enum;

final class OptionsFood
{
    public const FOOD_BARBQ = 'BARbq';
    public const FOOD_STAKE = 'Мастерстейк';
    public const FOOD_HANBUZ = 'Ханбуз';
    public const FOOD_DENER = 'Денер';
    public const FOOD_BOXES = 'Коробки';

    public static function toArray(): array
    {
        $newRef = new \ReflectionClass(self::class);
        return array_values($newRef->getConstants(\ReflectionClassConstant::IS_PUBLIC));

    }

}
