<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable =
        [
            'name',
            'user_id',
            'user_name',
            'poll_id',
            'price',
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
