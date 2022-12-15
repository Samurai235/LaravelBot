<?php

declare(strict_types=1);

namespace App\Enum;

use Illuminate\Validation\Rules\Enum;
use MyCLabs\Enum\Enum as EnumAlias;

final class OptionsFood extends EnumAlias
{
    public const FOOD = 'food';
    public const FOOD1 = 'food1';
    public const FOOD_STAKE = 'stake';
    public const FOOD_BUZ = 'buz';
}
