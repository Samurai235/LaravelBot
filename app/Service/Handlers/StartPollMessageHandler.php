<?php

declare(strict_types=1);

namespace App\Service\Handlers;

use App\Enum\OptionsFood;
use App\Service\HandlersInterface;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\BaseObject;
use Telegram\Bot\Objects\Message;

final class StartPollMessageHandler implements HandlersInterface
{
    public function supports(BaseObject $method): bool
    {
        return $method instanceof Message && ($method->text === '/startpoll'
                || $method->text === '/startpoll@MyTelegramDeliveryBot');
    }

    public function handle(BaseObject $method): void
    {
        /** @var Message $method */
        $tg = Telegram::bot('mybot');

        $checkActivePolls = \App\Models\Poll::all()
            ->where('active', true)
            ->first();

        if ($checkActivePolls) {
            throw new \RuntimeException('Уже есть незавершенные опросы требуется /stoppoll');
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $createdPoll = $tg->sendPoll(
            [
                'chat_id' => config('telegram.bots.mybot.chat_id'),
                'question' => 'Что заказываем?',
                'options' => json_encode(OptionsFood::toArray()),
                'is_anonymous' => false,
            ]
        );

        $checkPoll = \App\Models\Poll::where('id', $method->getPollId())
            ->where('active', true)
            ->first();

        if (!$checkPoll) {
            \App\Models\Poll::create([
                'id' => $createdPoll['poll']['id'],
                'message_id' => $createdPoll['message_id'],
                'chat_id' => $createdPoll['chat']['id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
