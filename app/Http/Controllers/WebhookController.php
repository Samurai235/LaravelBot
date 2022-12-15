<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\OptionsFood;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

final class WebhookController extends Controller
{
    public function index(): Response
    {
        $tg = Telegram::bot('mybot');

//        $tg->sendMessage(
//            [
//                'chat_id' => -893046653,//env('TG_CHAT_ID'),
//                'text' => 'Хули палишь',
//            ]
//        );

        if ($tg->getWebhookUpdate()->message?->text === '/startpoll') {
            /** @noinspection PhpUnhandledExceptionInspection */
            $tg->sendPoll(
                [
                    'chat_id' => 350318212, //env('TG_CHAT_ID'),
                    'question' => 'botsalam',
                    'options' => array_values(OptionsFood::toArray()),
                    'is_anonymous' => false,
                    'open_period' => 1800,
                ]
            );
        }

            $r = $tg->getWebhookUpdate(false);

           // $pollAnswer = $r->pollAnswer;

          //  $optionIds = json_encode($pollAnswer->optionIds);

        return new Response();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
