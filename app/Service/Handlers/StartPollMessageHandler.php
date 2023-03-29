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
        return $method instanceof Message && $method->text === '/startpoll';
    }

    public function handle(BaseObject $method): void
    {
        /** @var Message $method */
        $tg = Telegram::bot('mybot');

        /** @noinspection PhpUnhandledExceptionInspection */
        $createdPoll = $tg->sendPoll(
            [
                'chat_id' => config('telegram.bots.mybot.chat_id'),
                'question' => 'Что заказываем?',
                'options' => json_encode(OptionsFood::toArray()),
                'is_anonymous' => false,
            ]
        );
        //создать запись в базу

    }
}
