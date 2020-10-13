<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class Part implements MessageInterface
{
    private array $channels;

    public function __construct(array $channels)
    {
        $this->channels = $channels;
    }

    public function toString(): string
    {
        $channels = implode(',', $this->channels);
        return "PART $channels";
    }
}