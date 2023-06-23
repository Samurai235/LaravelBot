<?php

declare(strict_types=1);

namespace App\Service\Handlers;

use App\Enum\OptionsFood;
use App\Service\HandlersInterface;

use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\BaseObject;
use Telegram\Bot\Objects\Message;

final class StopPollMessageHandler implements HandlersInterface
{
    public function supports(BaseObject $method): bool
    {
        return $method instanceof Message && ($method->text === '/stoppoll'
                || $method->text === '/stoppoll@MyTelegramDeliveryBot');
    }

    /**
     * @throws TelegramSDKException
     */
    public function handle(BaseObject $method): void
    {
        /** @var Message $method */
        $tg = Telegram::bot('mybot');

        $activePoll = \App\Models\Poll::all()
            ->where('active', true)
            ->last();

        if ($activePoll) {

            $stopPoll = $tg->stopPoll([
                'chat_id' => $activePoll->chat_id,
                'message_id' => (int)$activePoll->message_id,
            ]);

            \App\Models\Poll::where('id', $activePoll->id)
                ->update([
                    'active' => false,
                    'updated_at' => now(),
                ]);

            $winKey = null;
            foreach ($stopPoll->options as $key => $option) {
                if ($option->voterCount === 0) {
                    continue;
                }
                $winCount = $option->voterCount;
                $winKey = $key;
                if ($winCount < $option->voterCount) {
                    $winKey = $key;
                } elseif ($winCount === $option->voterCount) {
                    $winKey = rand($winKey, $key );
                }
            }

            if ($winKey !== null) {
                $tg->sendMessage([
                    'chat_id' => $activePoll->chat_id,
                    'parse_mode' => 'HTML',
                    'text' => 'Кушаем из: ' . $stopPoll->options[$winKey]['text'] . ". \n"
                        . 'Для оформления заказа напишите <code>/order</code> и список заказа по шаблону - ' . "\n" . '"/order ' . "\n" . 'Блюдо1' . "\n" . 'Блюдо2 ' . "\n" . ': общая цена заказа"'
                ]);

            } else {
                $tg->sendMessage([
                    'chat_id' => $activePoll->chat_id,
                    'text' => 'Ничего не заказываем, пустой результат голосования'
                ]);
            }

        }

    }
}
