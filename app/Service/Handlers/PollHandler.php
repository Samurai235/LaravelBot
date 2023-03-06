<?php

declare(strict_types=1);

namespace App\Service\Handlers;

use App\Service\HandlersInterface;

use Telegram\Bot\Objects\BaseObject;
use Telegram\Bot\Objects\PollAnswer;
use Illuminate\Support\Facades\DB;

final class PollHandler implements HandlersInterface
{
    public function supports(BaseObject $method): bool
    {
        return $method instanceof PollAnswer;
    }

    public function handle(BaseObject $method): void
    {
        /** @var PollAnswer $method */

        $checkUsers = DB::table('poll_answers')
            ->select('user')
            ->where('user', $method->user->id)
            ->where('poll_id', $method->getPollId())
            ->first();

        $options = '';
        foreach ($method->optionIds as $item) {
            $options .= $item . ';';
        }

        if ($checkUsers) {
            DB::table('poll_answers')
                ->where('user', $checkUsers->user)
                ->where('poll_id', $method->getPollId())
                ->update([
                    'poll_options' => $options,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('poll_answers')->insert([
                'poll_id' => $method->getPollId(),
                'user' => $method->user->id,
                'poll_options' => $options,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
