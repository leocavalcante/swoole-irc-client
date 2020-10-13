<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class Password implements MessageInterface
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function toString(): string
    {
        return "PASS {$this->password}";
    }
}