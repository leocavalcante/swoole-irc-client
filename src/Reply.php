<?php declare(strict_types=1);

namespace SwooleIrc;

final class Reply
{
    // Raw IRC protocol
    public string $message;
    public string $prefix;
    public int $command;
    public string $middle;
    public string $trailing;

    // Sugar
    public string $nick;
    public ?string $channel;
    public string $text;

    public static function parse(string $message): self
    {
        preg_match('#^(?::(\S+)\s+)?(\S+)\s+([^:]+)?(:\s*(.+))?$#', $message, $matches);

        $reply = new self();
        $reply->message = $matches[0];
        $reply->prefix = $matches[1];
        $reply->command = (int)$matches[2];
        $reply->middle = $matches[3];
        $reply->trailing = $matches[4] ?? ':';
        $middle_parts = explode(' ', $reply->middle, 2);
        $reply->nick = trim($middle_parts[0]);
        $reply->channel = count($middle_parts) > 1 ? trim($middle_parts[1]) : null;
        $reply->text = trim(substr($reply->trailing, 1));
        return $reply;
    }
}