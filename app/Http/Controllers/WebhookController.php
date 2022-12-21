<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\OptionsFood;
use App\Models\PollAnswer;
use App\Repositories\PollAnswerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

final class WebhookController extends Controller
{
    public function __construct(private PollAnswerRepository $answerRepository,)
    {
    }

    public function index(): Response
    {
        $tg = Telegram::bot('mybot');

//        $tg->sendMessage(
//            [
//                'chat_id' => -893046653,//env('TG_CHAT_ID'),
//                'text' => 'Хули палишь',
//            ]
//        );
        $getWebhookUpdate = $tg->getWebhookUpdate();

        if ($getWebhookUpdate->message?->text === '/startpoll') {
            /** @noinspection PhpUnhandledExceptionInspection */
            $tg->sendPoll(
                [
                    'chat_id' => env('CHAT_ID'),
                    'question' => 'botsalam',
                    'options' => array_values(OptionsFood::toArray()),
                    'is_anonymous' => false,
                    'open_period' => 1800,
                ]
            );
        }

        if ($tg->getWebhookUpdate()->pollAnswer) {
            $pollAnswer = new PollAnswer(
                $getWebhookUpdate->pollAnswer->pollId,
                $getWebhookUpdate->pollAnswer->user,
                $getWebhookUpdate->pollAnswer->optionIds
            );
            $this->answerRepository->add($pollAnswer);
        }

        $r = $tg->getWebhookUpdate(false);

        // $pollAnswer = $r->pollAnswer;

        //  $optionIds = json_encode($pollAnswer->optionIds);

        return new Response();
    }
}
