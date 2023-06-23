<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Service\Handlers\OrderHandler;
use App\Service\Handlers\StartPollMessageHandler;
use App\Service\Handlers\PollHandler;
use App\Service\Handlers\StopPollMessageHandler;
use App\Service\Handlers\StopDeliveryHandler;
use Illuminate\Http\Response;
use Telegram\Bot\Laravel\Facades\Telegram;

final class WebhookController extends Controller
{
    public function __construct(
        private PollHandler             $pollHandler,
        private StartPollMessageHandler $startPollMessageHandler,
        private StopPollMessageHandler  $stopPollMessageHandler,
        private OrderHandler  $orderHandler,
        private StopDeliveryHandler  $deliveryHandler,
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
        //сделать проверку если прошло 30 мин вызывать stoppoll

        $collection = [];
        $collection[] = $this->pollHandler;
        $collection[] = $this->startPollMessageHandler;
        $collection[] = $this->stopPollMessageHandler;
        $collection[] = $this->orderHandler;
        $collection[] = $this->deliveryHandler;
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
