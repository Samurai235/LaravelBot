<?php

declare(strict_types=1);

namespace App\Service\Handlers;

use App\Service\HandlersInterface;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\BaseObject;
use Telegram\Bot\Objects\Message;

final class DeliveryPriceHandler implements HandlersInterface
{
    public function supports(BaseObject $method): bool
    {
        return $method instanceof Message
            && stripos($method->text, '/deliveryprice') === 0
            && !$method->from->isBot;
    }

    public function handle(BaseObject $method): void
    {
        /** @var Message $method */
        $tg = Telegram::bot('mybot');

        $lastClosedPoll = \App\Models\Poll::where('active', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastClosedPoll) {
            throw new \RuntimeException('Не найдено опроса. Воспользуйтесь командой запуска');
        }

        $orders = \App\Models\Order::where('poll_id', (string)$lastClosedPoll->id)
            ->get();

        if (!$orders) {
            throw new \RuntimeException('Не найдено заказов для последнего опроса. Воспользуйтесь командой для заказа');
        }

        $deliveryPrice = (int)preg_replace('/[^,.0-9]/', '', $method->text);
        $clientPrice = $deliveryPrice / (int)$orders->count();
        $resultMessage = '';

        foreach ($orders as $order) {
            $resultMessage .= '<b>' . $order->user_name . '</b>' . ' торчит: ' . $order->price + $clientPrice .
                ' руб' . "\n";
        }

        $tg->sendMessage([
            'chat_id' => $method->chat->id,
            'parse_mode' => 'HTML',
            'text' => 'Итоговая сумма с доставкой: ' . "\n" . $resultMessage
        ]);
    }
}
