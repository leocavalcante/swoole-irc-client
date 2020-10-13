<?php declare(strict_types=1);

namespace SwooleIrc;

interface MessageInterface
{
    public const CRLF = "\r\n";
    public function toString(): string;
}