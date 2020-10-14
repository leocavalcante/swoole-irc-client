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

    public static function create(): self
    {
        return new self(new Coroutine\Client(SWOOLE_SOCK_TCP));
    }

    public function __construct(Coroutine\Client $client)
    {
        $this->client = $client;
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

        return $this;
    }

    public function listen(ReplyHandlerInterface $handler): self
    {
        go(function () use ($handler) {
           while ($this->client->connected) {
               if ($buffer = $this->client->recv(30)) {
                   foreach (explode("\r\n", $buffer) as $message) {
                       $message = trim($message);

                       if (empty($message)) {
                           continue;
                       }

                       if (isset($this->logger)) {
                           $this->logger->debug($message);
                       }

                       preg_match('#^(?::(\S+)\s+)?(\S+)\s+([^:]+)?(:\s*(.+))?$#', $message,$matches);
                       $handler->onReply(Reply::createFromMatches($matches), $this);
                   }
               }
           }
        });

        return $this;
    }

    public function pass(string $password): self
    {
        $this->send(new Message\Password($password));
        return $this;
    }

    public function nick(string $nickname): self
    {
        $this->send(new Message\Nick($nickname));
        return $this;
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

    public function ping(string $server)
    {
        $this->send(new Message\Ping($server));
    }

    public function pong(string $daemon)
    {
        $this->send(new Message\Pong($daemon));
    }

    public function send(MessageInterface $message)
    {
        $this->writeln($message->toString());
    }

    public function writeln(string $message)
    {
        if (isset($this->logger)) {
            $this->logger->debug($message);
        }

        $this->client->send("$message\r\n");
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }
}