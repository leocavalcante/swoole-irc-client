<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class Operator implements MessageInterface
{
    private string $user;
    private string $password;

    public function __construct(string $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function toString(): string
    {
        return "OPER {$this->user} {$this->password}";
    }
}