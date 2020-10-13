<?php declare(strict_types=1);

namespace SwooleIrc\Message;

use SwooleIrc\MessageInterface;

final class User implements MessageInterface
{
    private string $username;
    private string $hostname;
    private string $servername;
    private string $realName;

    public function __construct(string $username, string $hostname, string $servername, string $realName)
    {
        $this->username = $username;
        $this->hostname = $hostname;
        $this->servername = $servername;
        $this->realName = $realName;
    }

    public function toString(): string
    {
        return "USER {$this->username} {$this->hostname} {$this->servername} :{$this->realName}";
    }
}