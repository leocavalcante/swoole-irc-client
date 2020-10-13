<?php declare(strict_types=1);

namespace SwooleIrc\Test;

use Swoole\Coroutine;
use SwooleIrc\Client;
use function PHPUnit\Framework\exactly;

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

it('sends quit message', function () {
    $this->client->expects($this->once())
        ->method('send')
        ->with("QUIT\r\n");

    $this->irc->quit();
});

it('joins one or more channels', function () {
    $this->client->expects(exactly(2))
        ->method('send')
        ->withConsecutive(
            ["JOIN #foobar\r\n"],
            ["JOIN #foo,&bar\r\n"],
        );

    $this->irc->join(['#foobar']);
    $this->irc->join(['#foo', '&bar']);
});

it('joins one or more channels wtih keys', function () {
    $this->client->expects(exactly(2))
        ->method('send')
        ->withConsecutive(
            ["JOIN #foo,&bar fubar\r\n"],
            ["JOIN #foo,#bar fubar,foobar\r\n"],
        );

    $this->irc->join(['#foo', '&bar'], ['fubar']);
    $this->irc->join(['#foo', '#bar'], ['fubar', 'foobar']);
});

it('leaves one or more channels', function () {
    $this->client->expects(exactly(2))
        ->method('send')
        ->withConsecutive(
            ["PART #foobar\r\n"],
            ["PART #foo,&bar\r\n"],
        );

    $this->irc->part(['#foobar']);
    $this->irc->part(['#foo', '&bar']);
});

it('sends private messages to one or more receivers', function () {
    $this->client->expects(exactly(2))
        ->method('send')
        ->withConsecutive(
            ["PRIVMSG #foobar :Hello, World!\r\n"],
            ["PRIVMSG #foo,&bar :Hello, World!\r\n"],
        );

    $this->irc->privmsg(['#foobar'], 'Hello, World!');
    $this->irc->privmsg(['#foo', '&bar'], 'Hello, World!');
});

it('sends ping/pong messages', function () {
    $this->client->expects(exactly(2))
        ->method('send')
        ->withConsecutive(
            ["PING test\r\n"],
            ["PONG test\r\n"],
        );

    $this->irc->ping('test');
    $this->irc->pong('test');
});
