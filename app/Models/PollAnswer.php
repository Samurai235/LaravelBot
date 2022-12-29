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
     * @var string
     */
    private string $pollId;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var array
     */
    private array $optionIds;

    public function __construct(
        string $pollId,
        User $user,
        array $optionIds,
        array $attributes = []
    ) {
        parent::__construct($attributes);

        $this->pollId = $pollId;
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

    public function getPollId(): string
    {
        return $this->pollId;
    }

    public function setPollId(string $pollId): self
    {
        $this->pollId = $pollId;

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

    public function getOptionIds(): array
    {
        return $this->optionIds;
    }

    public function setOptionIds(array $optionIds): self
    {
        $this->optionIds = $optionIds;

        return $this;
    }
}
