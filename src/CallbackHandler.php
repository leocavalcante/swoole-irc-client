<?php declare(strict_types=1);

namespace SwooleIrc;

final class CallbackHandler implements HandlerInterface
{
    /** @var callable */
    private $replyCallback;
    /** @var callable|null */
    private $connectCallback;

    public static function reply(callable $replyCallback): self
    {
        return new self($replyCallback);
    }

    public function __construct(callable $replyCallback, ?callable $connectCallback = null)
    {
        $this->replyCallback = $replyCallback;
        $this->connectCallback = $connectCallback;
    }

    public function connect(callable $connectCallback): self
    {
        $this->connectCallback = $connectCallback;
        return $this;
    }

    public function onConnect(Client $client): void
    {
        if ($this->connectCallback !== null) {
            ($this->connectCallback)($client);
        }
    }

    public function onReply(Reply $reply, Client $client): void
    {
        ($this->replyCallback)($reply, $client);
    }
}