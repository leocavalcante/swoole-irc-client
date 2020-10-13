<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class Nick implements MessageInterface
{
    private string $nickname;

    public function __construct(string $nickname)
    {
        $this->nickname = $nickname;
    }

    public function toString(): string
    {
        return "NICK {$this->nickname}";
    }
}