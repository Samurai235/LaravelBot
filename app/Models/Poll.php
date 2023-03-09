<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $table = 'polls';

    protected $fillable =
        [
            'id',
            'message_id',
            'chat_id',
        ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function getAnswers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PollAnswer::class, 'poll_id', 'id');
    }


}
