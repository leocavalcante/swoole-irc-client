<?php declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Swoole\Coroutine as Co;
use SwooleIrc\Client;
use SwooleIrc\Reply;
use SwooleIrc\ReplyHandlerInterface;

require_once __DIR__ . '/../../vendor/autoload.php';

Co\run(static function (): void {
    $logger = new Logger('Log');
    $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

    Client::create()
        ->setLogger($logger)
        ->connect('irc.chat.twitch.tv', 6667, false)
        ->pass('oauth:' . getenv('TWITCH_OAUTH_TOKEN'))
        ->nick('leocavalcantee')
        ->listen(new class implements ReplyHandlerInterface {
            public function onReply(Reply $reply, Client $client): void
            {
                if ($reply->text === "You are in a maze of twisty passages, all alike.") {
                    $client->join(['#leocavalcantee']);
                }

                if ($reply->text === '!hello') {
                    $client->privmsg(['#leocavalcantee'], "Hello, {$reply->nick}");
                }
            }
        });
});


