<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\UuidInterface;
use Telegram\Bot\Objects\User;

class Poll extends Model
{
    use HasFactory;

    private string $poll_id;
    private string $message_id;
    private string $chat_id;
    private bool $active;

    protected $table = 'polls';

    protected $fillable =
        [
            'poll_id',
            'message_id',
            'chat_id',
        ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var UuidInterface
     */
    private UuidInterface $id;

    /**
     * @var User
     */
    private User $user;

    public function __construct(
        User $user,
        string $poll_id,
        string $message_id,
        string $chat_id,
        bool $active,
        array $attributes = []
    )
    {
        parent::__construct($attributes);
        $this->user = $user;
        $this->poll_id = $poll_id;
        $this->message_id = $message_id;
        $this->chat_id = $chat_id;
        $this->active = $active;
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

    public function getPollId(): string
    {
        return $this->poll_id;
    }

    public function setPollId(string $poll_id): self
    {
        $this->poll_id = $poll_id;

        return $this;
    }

    public function getMessageId(): string
    {
        return $this->message_id;
    }

    public function setMessageId(string $message_id): self
    {
        $this->message_id = $message_id;

        return $this;
    }

    public function getChatId(): string
    {
        return $this->chat_id;
    }

    public function setChatId(string $chat_id): self
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAnswers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PollAnswer::class, 'poll_id', 'id');
    }


}
