<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class PrivateMsg implements MessageInterface
{
    private array $receivers;
    private string $text;

    public function __construct(array $receivers, string $text)
    {
        $this->receivers = $receivers;
        $this->text = $text;
    }

    public function toString(): string
    {
        $receivers = implode(',', $this->receivers);
        return "PRIVMSG $receivers :{$this->text}";
    }
}