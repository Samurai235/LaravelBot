<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\PollAnswerInterface;
use App\Models\PollAnswer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class PollAnswerRepository implements PollAnswerInterface
{
    public function all(): Collection
    {
        return PollAnswer::all();
    }

    public function getByPoll(PollAnswer $pollAnswer): \Illuminate\Support\Collection
    {
        return DB::table('poll_answers')->where('poll_id', $pollAnswer->getPollId())->get();
    }

    public function add(PollAnswer $pollAnswer)
    {
        DB::table('poll_answers')->insert([
        ]);
    }
}
