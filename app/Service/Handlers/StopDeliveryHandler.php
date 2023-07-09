<?php

declare(strict_types=1);

namespace App\Service\Handlers;

use App\Service\HandlersInterface;

use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\BaseObject;
use Telegram\Bot\Objects\Message;

final class StopDeliveryHandler implements HandlersInterface
{
    public function supports(BaseObject $method): bool
    {
        return $method instanceof Message
            && $method->text !== null
            && ($method->text === '/stopdelivery@MyTelegramDeliveryBot');
    }

    /**
     * @throws TelegramSDKException
     */
    public function handle(BaseObject $method): void
    {
        /** @var Message $method */
        $tg = Telegram::bot('mybot');

        $lastClosedPoll = \App\Models\Poll::all()
            ->where('active', false)
            ->last();

        if ($lastClosedPoll) {

            $orders = \App\Models\Order::where('poll_id', (string)$lastClosedPoll->id)
                ->get();

            if (!$orders) {
                throw new \RuntimeException('Не найдены заказы по последнему опросу');
            }

            $orderArr = [];
            $allPrice = 0;

            foreach ($orders as $order) {
                $orderArr[] = explode(';', $order->name);
                (float)$allPrice = $order->price + $allPrice;
            }

            $orderText = '';
            foreach ($orderArr as $arItem) {
                foreach ($arItem as $item) {
                    $orderText .=  $item . "\n";
                }
            }

           $closedPoll = \App\Models\Poll::where('id', $lastClosedPoll->id)
                ->update([
                    'closed' => true,
                    'updated_at' => now(),
                ]);

            $tg->sendMessage([
                'chat_id' => $method->chat->id,
                'parse_mode' => 'HTML',
                'text' => 'Общий заказ для последнего опроса:' . "\n"
                    . $orderText . "\n"
                    . '<b>Стоимость: ' . $allPrice . '</b>'
            ]);
        }

    }
}
