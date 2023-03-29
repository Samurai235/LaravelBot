<?php

declare(strict_types=1);

namespace App\Service\Handlers;

use App\Service\HandlersInterface;

use Telegram\Bot\Objects\BaseObject;
use Telegram\Bot\Objects\PollAnswer;

final class PollHandler implements HandlersInterface
{
    public function supports(BaseObject $method): bool
    {
        return $method instanceof PollAnswer;
    }

    public function handle(BaseObject $method): void
    {
        /** @var PollAnswer $method */

        $checkPoll = \App\Models\Poll::where('id', $method->getPollId())
            ->where('active', true)
            ->first();

        if (!$checkPoll) {
            throw new \RuntimeException('Не найден id опроса');
        }

        $checkUsers = \App\Models\PollAnswer::where('user', $method->user->id)
            ->where('poll_id', $method->getPollId())
            ->select('user')
            ->first();

        $options = '';
        foreach ($method->optionIds as $item) {
            $options .= $item . ';';
        }

        if ($checkUsers) {
            \App\Models\PollAnswer::where('user', $checkUsers->user)
                ->where('poll_id', $method->getPollId())
                ->select('user')
                ->update([
                    'poll_options' => $options,
                    'updated_at' => now(),
                ]);
        } else {
            \App\Models\PollAnswer::create([
                'poll_id' => $method->getPollId(),
                'user' => $method->user->id,
                'poll_options' => $options,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
