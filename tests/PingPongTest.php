<?php declare(strict_types=1);

namespace SwooleIrc\Test;

use Swoole\Coroutine;
use SwooleIrc\Client;

it('reply with PONG when receives a PING command', function () {
    $client = $this->getMockBuilder(Coroutine\Client::class)
        ->disableOriginalConstructor()
        ->getMock();

    $client->expects($this->once())
        ->method('send')
        ->with("PONG :server.com\r\n");

    $irc = new Client($client);
    $irc->handleMessage('PING :server.com');
});