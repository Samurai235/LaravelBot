<?php

namespace App\Service;

use Telegram\Bot\Objects\BaseObject;

interface HandlersInterface
{
    public function supports(BaseObject $method): bool;

    public function handle(BaseObject $method): void;

}
