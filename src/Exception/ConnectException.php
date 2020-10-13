<?php declare(strict_types=1);

namespace SwooleIrc\Exception;

final class ConnectException extends \Exception
{
    public function __construct(string $errorMessage)
    {
        parent::__construct("Error connecting to IRC server: $errorMessage");
    }
}