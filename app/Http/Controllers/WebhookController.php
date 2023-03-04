<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\OptionsFood;
use App\Repositories\PollAnswerRepository;
use App\Service\PollHandler;
use Illuminate\Http\Response;
use Telegram\Bot\Laravel\Facades\Telegram;

final class WebhookController extends Controller
{
    public function __construct(private PollAnswerRepository $answerRepository, private PollHandler $pollHandler)
    {
    }

    public function index(): Response
    {
        $tg = Telegram::bot('mybot');

        $getWebhookUpdate = $tg->getWebhookUpdate();
        if ($getWebhookUpdate->message?->text === '/startpoll') {

            /** @noinspection PhpUnhandledExceptionInspection */
            $tg->sendPoll(
                [
                    'chat_id' => config('telegram.bots.mybot.chat_id'),
                    'question' => 'Что заказываем?',
                    'options' => json_encode(array_values(OptionsFood::toArray())),
                    'is_anonymous' => false,
                ]
            );
        }

        $collection = [];
        $collection[] = $this->pollHandler;

        foreach (
            $collection as $handler
        ) {

            if (!$handler->supports($getWebhookUpdate->getRelatedObject())) {
                continue;
            }

            $handler->handle($getWebhookUpdate->getRelatedObject());
        }

        return new Response();
    }


}
