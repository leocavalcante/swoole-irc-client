<?php declare(strict_types=1);

namespace SwooleIrc;

use Swoole\Coroutine;
use SwooleIrc\Exception;
use SwooleIrc\Message;

class Client
{
    private Coroutine\Client $client;

    public function __construct(?Coroutine\Client $client = null)
    {
        $this->client = $client ?? new Coroutine\Client(SWOOLE_SOCK_TCP);
    }

    public function connect(string $host, int $port): void
    {
        if (!$this->client->connect($host, $port)) {
            throw new Exception\ConnectException($this->client->errMsg);
        }
    }

    public function pass(string $password)
    {
        $this->send(new Message\Password($password));
    }

    public function nick(string $nickname)
    {
        $this->send(new Message\Nick($nickname));
    }

    public function user(string $username, string $hostname, string $servername, string $realName)
    {
        $this->send(new Message\User($username, $hostname, $servername, $realName));
    }

    public function oper(string $user, string $password)
    {
        $this->send(new Message\Operator($user, $password));
    }

    public function quit()
    {
        $this->send(new Message\Quit());
    }

    public function join(array $channels, array $keys = [])
    {
        $this->send(new Message\Join($channels, $keys));
    }

    public function part(array $channels)
    {
        $this->send(new Message\Part($channels));
    }

    public function privmsg(array $receivers, string $text)
    {
        $this->send(new Message\PrivateMsg($receivers, $text));
    }

    public function send(MessageInterface $message)
    {
        $this->writeln($message->toString());
    }

    public function writeln(string $message)
    {
        $this->client->send($message . MessageInterface::CRLF);
    }
}