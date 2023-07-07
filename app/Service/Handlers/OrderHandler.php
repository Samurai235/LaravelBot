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
            && $method->text !== null
            && stripos($method->text, '/order') === 0
            && !$method->from->isBot;
    }

    public function handle(BaseObject $method): void
    {
        /** @var Message $method */

        $lastClosedPoll = \App\Models\Poll::all()
            ->where('active', false)
            ->where('closed', false)
            ->last();

        if (!$lastClosedPoll) {
            throw new \RuntimeException('Не найдено активного опроса');
        }

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
                $item = str_replace(',', '.', $item);

                if (preg_match("#^[0-9\.]+$#", $item)) {
                    $orders['price'] = (float)$item;
                } else {
                    throw new \RuntimeException('В стоимости недопустимы символы');
                }
                continue;
            }
            $orders['user_id'] = $method->from->id;
            $orders['user_name'] = $method->from->first_name;
            $name .= preg_replace('/\W+/u', '', $item) . ';';
        }
        $orders['order'] = htmlspecialchars($name);
        unset($item);

        $checkDuplicate = \App\Models\Order::where('user_id', $orders['user_id'])
            ->where('poll_id', $lastClosedPoll->id)
            ->first();

        if ($checkDuplicate) {
            \App\Models\Order::where('user_id', $orders['user_id'])
                ->where('poll_id', $lastClosedPoll->id)
                ->update([
                    'name' => $orders['order'],
                    'price' => round($orders['price'],2),
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
                'price' => round($orders['price'],2),
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
