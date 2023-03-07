<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/webhook', function () {
    $response =  \Illuminate\Support\Facades\Http::get('https://api.tlgr.org/bot'. config('telegram.bots.mybot.token'). '/setWebhook?url=https://2a67-5-44-168-99.in.ngrok.io/');
dd($response);
});

Route::post('/', [WebhookController::class,'index']);


