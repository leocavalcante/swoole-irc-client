<?php declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Swoole\Coroutine as Co;
use SwooleIrc\Client;
use SwooleIrc\Reply;
use SwooleIrc\HandlerInterface;

require_once __DIR__ . '/../../vendor/autoload.php';

class BipBopMyBot implements HandlerInterface
{
    private string $twOauthToken;

    public function __construct(string $token)
    {
        $this->twOauthToken = $token;
    }

    public function onConnect(Client $client): void
    {
        $client
            ->pass("oauth:{$this->twOauthToken}")
            ->nick('leocavalcantee');
    }

    public function onReply(Reply $reply, Client $client): void
    {
        if ($reply->text === "You are in a maze of twisty passages, all alike.") {
            $client->join(['#leocavalcantee']);
        }

        if ($reply->text === '!hello') {
            $client->privmsg(['#leocavalcantee'], "Hello, {$reply->nick}!");
        }
    }
}

Co\run(static function (): void {
    $logger = new Logger('BipBop');
    $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

    Client::withHandler(new BipBopMyBot(getenv('TWITCH_OAUTH_TOKEN')))
        ->logger($logger)
        ->connect('irc.chat.twitch.tv', 6667, false)
        ->start();
});