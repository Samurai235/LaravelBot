<?php

namespace App\Interfaces;

use App\Models\PollAnswer;

interface PollAnswerInterface
{
    public function all();

    public function getByPoll(PollAnswer $pollAnswer);

    public function add(PollAnswer $pollAnswer);
}
