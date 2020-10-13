<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class Join implements MessageInterface
{
    private array $channels;
    private array $keys;

    public function __construct(array $channels, array $keys = [])
    {
        $this->channels = $channels;
        $this->keys = $keys;
    }

    public function toString(): string
    {
        $channels = implode(',', $this->channels);

        if (empty($this->keys)) {
            return "JOIN $channels";
        }

        $keys = implode(',', $this->keys);
        return "JOIN $channels $keys";
    }
}