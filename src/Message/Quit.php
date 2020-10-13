<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class Quit implements MessageInterface
{
    public function toString(): string
    {
        return "QUIT";
    }
}