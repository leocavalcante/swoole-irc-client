<?php declare(strict_types=1);

namespace SwooleIrc;

interface MessageInterface
{
    public function toString(): string;
}