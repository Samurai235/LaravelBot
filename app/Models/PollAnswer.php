<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\UuidInterface;
use Telegram\Bot\Objects\User;

final class PollAnswer extends Model
{
    use HasFactory;
    /**
     * @var UuidInterface
     */
    private UuidInterface $id;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var array
     */
    private string $optionIds;

    public function __construct(
        User $user,
        string $optionIds,
        array $attributes = []
    ) {
        parent::__construct($attributes);
        $this->user = $user;
        $this->optionIds = $optionIds;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOptionIds(): string
    {
        return $this->optionIds;
    }

    public function setOptionIds(string $optionIds): self
    {
        $this->optionIds = $optionIds;

        return $this;
    }

    public function getPoll(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Poll::class, 'poll_id', 'id');
    }
}
