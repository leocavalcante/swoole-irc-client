<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class Pong implements MessageInterface
{
    private string $daemon;

    public function __construct(string $daemon)
    {
        $this->daemon = $daemon;
    }

    public function toString(): string
    {
        return "PONG {$this->daemon}";
    }
}