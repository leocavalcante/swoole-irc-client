<?php declare(strict_types=1);

namespace SwooleIrc;

use Psr\Log\LoggerInterface;
use Swoole\Coroutine;
use SwooleIrc\Exception;
use SwooleIrc\Message;

class Client
{
    private Coroutine\Client $client;
    private LoggerInterface $logger;
    private HandlerInterface $handler;

    public function __construct(Coroutine\Client $client)
    {
        $this->client = $client;
    }

    public static function create(): self
    {
        return new self(new Coroutine\Client(SWOOLE_SOCK_TCP));
    }

    public static function withHandler(HandlerInterface $handler): self
    {
        return self::create()->handler($handler);
    }

    public function connect(string $host, int $port, bool $ssl = true): self
    {
        if ($ssl) {
            $this->client->enableSSL();
        }

        if (!$this->client->connect($host, $port)) {
            throw new Exception\ConnectException($this->client->errMsg);
        }

        if (isset($this->logger)) {
            $this->logger->info("Connected to $host at port $port");
        }

        if (isset($this->handler)) {
            $this->handler->onConnect($this);
        }

        return $this;
    }

    public function start(): void
    {
        go(function () {
            while ($this->client->connected) {
                if ($buffer = $this->client->recv(30)) {
                    foreach (explode(MessageInterface::CRLF, $buffer) as $message) {
                        $message = trim($message);

                        if (empty($message)) {
                            continue;
                        }

                        if (isset($this->logger)) {
                            $this->logger->debug($message);
                        }

                        $this->handleMessage($message);
                    }
                }
            }
        });
    }

    public function handleMessage(string $message): void
    {
        if (strpos($message, 'PING') === 0) {
            $this->writeln(str_replace('PING', 'PONG', $message));
        }

        if (isset($this->handler)) {
            go(fn() => $this->handler->onReply(Reply::parse($message), $this));
        }
    }

    public function handler(HandlerInterface $handler): self
    {
        $this->handler = $handler;

        if ($this->client->connected) {
            $this->handler->onConnect($this);
        }

        return $this;
    }

    public function pass(string $password): self
    {
        $this->send(new Message\Password($password));
        return $this;
    }

    public function send(MessageInterface $message): self
    {
        $this->writeln($message->toString());
        return $this;
    }

    public function writeln(string $message): self
    {
        if (isset($this->logger)) {
            $this->logger->debug($message);
        }

        $this->client->send($message . MessageInterface::CRLF);
        return $this;
    }

    public function nick(string $nickname): self
    {
        $this->send(new Message\Nick($nickname));
        return $this;
    }

    public function user(string $username, string $hostname, string $servername, string $realName): self
    {
        $this->send(new Message\User($username, $hostname, $servername, $realName));
        return $this;
    }

    public function oper(string $user, string $password): self
    {
        $this->send(new Message\Operator($user, $password));
        return $this;
    }

    public function quit(): self
    {
        $this->send(new Message\Quit());
        $this->client->close();
        return $this;
    }

    public function join(array $channels, array $keys = []): self
    {
        $this->send(new Message\Join($channels, $keys));
        return $this;
    }

    public function part(array $channels): self
    {
        $this->send(new Message\Part($channels));
        return $this;
    }

    public function privmsg(array $receivers, string $text): self
    {
        $this->send(new Message\PrivateMsg($receivers, $text));
        return $this;
    }

    public function ping(string $server): self
    {
        $this->send(new Message\Ping($server));
        return $this;
    }

    public function pong(string $daemon): self
    {
        $this->send(new Message\Pong($daemon));
        return $this;
    }

    public function logger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }
}