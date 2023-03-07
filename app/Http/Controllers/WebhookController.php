<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\OptionsFood;
use App\Models\PollAnswer as PollAnswerModel;
use App\Repositories\PollAnswerRepository;
use App\Service\Handlers\PollHandler;
use Illuminate\Http\Response;
use Telegram\Bot\Laravel\Facades\Telegram;

final class WebhookController extends Controller
{
    public function __construct(
        private PollAnswerRepository $answerRepository,
        private PollHandler $pollHandler,
    )
    {
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function index(): Response
    {

        $tg = Telegram::bot('mybot');

        $getWebhookUpdate = $tg->getWebhookUpdate();

        if ($getWebhookUpdate->message?->text === '/startpoll') {

            /** @noinspection PhpUnhandledExceptionInspection */
            $createdPoll = $tg->sendPoll(
                [
                    'chat_id' => config('telegram.bots.mybot.chat_id'),
                    'question' => 'Что заказываем?',
                    'options' => json_encode(OptionsFood::toArray()),
                    'is_anonymous' => false,
                ]
            );

        }

//сущность poll: chat_id, poll_id, message_id, active
//        if ($getWebhookUpdate->message?->text === '/stoppoll') {
//            $tg->stopPoll([
//                'chat_id' => config('telegram.bots.mybot.chat_id'),
//                'message_id' => 390,
//
//            ]);
//        }


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
