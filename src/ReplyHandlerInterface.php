<?php declare(strict_types=1);

namespace SwooleIrc;

interface ReplyHandlerInterface
{
    public function onReply(Reply $reply, Client $client): void;
}