<?php declare(strict_types=1);

namespace SwooleIrc\Test;

use Swoole\Coroutine;
use SwooleIrc\Client;

beforeEach(function () {
    $this->client = $this->getMockBuilder(Coroutine\Client::class)
        ->disableOriginalConstructor()
        ->getMock();

    $this->irc = new Client($this->client);
});

it('connects to an irc server', function() {
    $this->client->expects($this->once())
        ->method('connect')
        ->with('test', 1234)
        ->willReturn(true);


    $this->irc->connect('test', 1234);
});

it('sends password message', function () {
    $this->client->expects($this->once())
        ->method('send')
        ->with("PASS test\r\n");

    $this->irc->pass('test');
});

it('sends nick message', function () {
    $this->client->expects($this->once())
        ->method('send')
        ->with("NICK test\r\n");

    $this->irc->nick('test');
});

it('sends user message', function () {
    $this->client->expects($this->once())
        ->method('send')
        ->with("USER guest tolmoon tolsun :Ronnie Reagan\r\n");

    $this->irc->user('guest', 'tolmoon', 'tolsun', 'Ronnie Reagan');
});

it('sends operator message', function () {
    $this->client->expects($this->once())
        ->method('send')
        ->with("OPER foo bar\r\n");

    $this->irc->oper('foo', 'bar');
});