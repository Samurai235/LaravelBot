<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetWebHook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:set
    {link : The link for webhook}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command set webhook';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::get('https://api.telegram.org/bot' . config('telegram.bots.mybot.token')
            . '/setWebhook?url=' . $this->argument('link'));
        if ($response->ok) {
            $this->info('Webhook command was successful!');
            return Command::SUCCESS;
        } else {
            $this->error('Webhook command was error!');
            $this->error(json_decode($response->body())->description);
            return Command::FAILURE;
        }
    }
}
