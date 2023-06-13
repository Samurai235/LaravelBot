<?php

declare(strict_types=1);

namespace App\Service\Handlers;

use App\Enum\OptionsFood;
use App\Service\HandlersInterface;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\BaseObject;
use Telegram\Bot\Objects\Message;

final class OrderHandler implements HandlersInterface
{
    public function supports(BaseObject $method): bool
    {
        return $method instanceof Message
            && stripos($method->text, '/order') === 0;
    }

    public function handle(BaseObject $method): void
    {
        /** @var Message $method */
        $tg = Telegram::bot('mybot');
        $orders = [];
        if (!$method->from->isBot) {
            $strToArrOrder = explode("\n", $method->text);

            foreach ($strToArrOrder as &$item) {
                if ($item === '/order') {
                    continue;
                }

                if (stripos($item, ':') === 0) {
                    $item = str_replace(':', '', $item);
                    if ((int)preg_match("/^\d+$/", $item)) {
                        $orders[] = (int)$item;
                    }
                }
            }
        }
        dd($orders);
    }
}
