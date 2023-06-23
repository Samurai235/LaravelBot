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
            && stripos($method->text, '/order') === 0
            && !$method->from->isBot;
    }

    public function handle(BaseObject $method): void
    {
        /** @var Message $method */
        $tg = Telegram::bot('mybot');
        $orders = [];

        $strToArrOrder = explode("\n", $method->text);
        $name = '';
        foreach ($strToArrOrder as &$item) {

            if ($item === '/order') {
                continue;
            }

            if (stripos($item, ':') === 0) {
                $item = str_replace(':', '', $item);
                if ((int)preg_match("/^\d+$/", $item)) {
                    $orders['price'] = (float)$item;
                }
                continue;
            }

            $orders['user_id'] = $method->from->id;
            $orders['user_name'] = $method->from->first_name;
            $name .= preg_replace('/\W+/u', '', $item) . ';';
        }
        $orders['order'] = htmlspecialchars($name);
        unset($item);

        $lastClosedPoll = \App\Models\Poll::where('active', false)
            ->orderBy('created_at', 'desc')
            ->first();

        $checkDuplicate = \App\Models\Order::where('user_id', $orders['user_id'])
            ->where('poll_id', $lastClosedPoll->id)
            ->first();

        if ($lastClosedPoll) {
            if ($checkDuplicate) {
                \App\Models\Order::where('user_id', $orders['user_id'])
                    ->where('poll_id', $lastClosedPoll->id)
                    ->update([
                        'name' => $orders['order'],
                        'price' => $orders['price'],
                        'updated_at' => now(),
                    ]);

                $tg->sendMessage([
                    'chat_id' => $method->chat->id,
                    'parse_mode' => 'HTML',
                    'text' => 'Обновил заказ от ' . $orders['user_name']
                ]);

            } else {
                \App\Models\Order::create([
                    'name' => $orders['order'],
                    'user_id' => $orders['user_id'],
                    'user_name' => $orders['user_name'],
                    'poll_id' => $lastClosedPoll->id,
                    'price' => $orders['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $tg->sendMessage([
                    'chat_id' => $method->chat->id,
                    'parse_mode' => 'HTML',
                    'text' => 'Создал заказ от ' . $orders['user_name']
                ]);
            }
        }
    }
}
