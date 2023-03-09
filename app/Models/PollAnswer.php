<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class PollAnswer extends Model
{
    use HasFactory;

    protected $table = 'poll_answers';

    protected $fillable =
        [
            'poll_id',
            'user',
        ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function getPoll(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Poll::class, 'poll_id', 'id');
    }
}
