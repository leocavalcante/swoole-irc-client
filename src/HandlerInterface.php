<?php declare(strict_types=1);

namespace SwooleIrc;

interface HandlerInterface
{
    public function onConnect(Client $client): void;
    public function onReply(Reply $reply, Client $client): void;
}