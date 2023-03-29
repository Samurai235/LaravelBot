<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\OptionsFood;
use App\Repositories\PollAnswerRepository;
use App\Service\Handlers\StartPollMessageHandler;
use App\Service\Handlers\PollHandler;
use Illuminate\Http\Response;
use Telegram\Bot\Laravel\Facades\Telegram;

final class WebhookController extends Controller
{
    public function __construct(
        private PollAnswerRepository    $answerRepository,
        private PollHandler             $pollHandler,
        private StartPollMessageHandler $messageHandler,
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
        $collection[] = $this->messageHandler;
        $relatedObject = $getWebhookUpdate->getRelatedObject();

        foreach (
            $collection as $handler
        ) {
            if (!$handler->supports($relatedObject)) {
                continue;
            }
            try {
                $handler->handle($relatedObject);
            } catch (\Throwable $error) {
                $tg->sendMessage([
                    'chat_id' => config('telegram.bots.mybot.chat_id'),
                    'text' => $error->getMessage()
                ]);
            }
        }


        return new Response();
    }


}
