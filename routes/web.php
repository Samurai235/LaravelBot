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

//Route::get('/', function () {
//    \Illuminate\Support\Facades\Http::get('https://api.tlgr.org/bot'.env('TELEGRAM_BOT_TOKEN'). '/setWebhook?url=https://5639-195-208-156-188.in.ngrok.io/webhook');
//});

Route::post('/webhook', [WebhookController::class,'index']);
//Route::get('/', [WebhookController::class,'index']);


