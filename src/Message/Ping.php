<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class Ping implements MessageInterface
{
    private string $server;

    public function __construct(string $server)
    {
        $this->server = $server;
    }

    public function toString(): string
    {
        return "PING {$this->server}";
    }
}