<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Service\Handlers\DeliveryPriceHandler;
use App\Service\Handlers\OrderHandler;
use App\Service\Handlers\StartPollMessageHandler;
use App\Service\Handlers\PollHandler;
use App\Service\Handlers\StopPollMessageHandler;
use App\Service\Handlers\StopDeliveryHandler;
use Illuminate\Http\Response;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

final class WebhookController extends Controller
{
    public function __construct(
        private PollHandler             $pollHandler,
        private StartPollMessageHandler $startPollMessageHandler,
        private StopPollMessageHandler  $stopPollMessageHandler,
        private OrderHandler            $orderHandler,
        private StopDeliveryHandler     $deliveryHandler,
        private DeliveryPriceHandler    $deliveryPriceHandler,
    ) {
    }

    /**
     * @throws TelegramSDKException
     */
    public function index(): Response
    {

        $tg = Telegram::bot('mybot');
        $getWebhookUpdate = $tg->getWebhookUpdate();
        //сделать проверку если прошло 30 мин вызывать stoppoll
        //так же сделать проверку, для вызова stopdelivery

        $collection = [];
        $collection[] = $this->pollHandler;
        $collection[] = $this->startPollMessageHandler;
        $collection[] = $this->stopPollMessageHandler;
        $collection[] = $this->orderHandler;
        $collection[] = $this->deliveryHandler;
        $collection[] = $this->deliveryPriceHandler;
        $relatedObject = $getWebhookUpdate->getRelatedObject();

        foreach (
            $collection as $handler
        ) {
            if (!$handler->supports($relatedObject)) {
                continue;
            }
            try {
                $handler->handle($relatedObject);
            } catch (Throwable $error) {
                $tg->sendMessage([
                    'chat_id' => config('telegram.bots.mybot.chat_id'),
                    'text' => $error->getMessage()
                ]);
            }
        }
        return new Response();
    }
}
