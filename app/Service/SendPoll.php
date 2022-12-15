<?php

declare(strict_types=1);

namespace App\Service;

use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Message as MessageObject;
use Telegram\Bot\Objects\Poll;

final class SendPoll
{
    /**
     * @var string
     */
    protected string $chatId;

    /**
     * @var string
     */
    protected string $question;

    /**
     * @var array
     */
    protected array $options;

    protected Api $telegram;

    public function sendPoll(
        string $chatId,
        string $question,
        array $options,
    ): MessageObject {
        $telegram = Telegram::bot('hg');
        /** @noinspection PhpUnhandledExceptionInspection */
        return $telegram->sendPoll([
            'chat_id' => $chatId,
            'question' => $question,
            'options' => $options,
        ]);
    }
}
