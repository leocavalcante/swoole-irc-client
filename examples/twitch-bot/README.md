## Twitch Chatbot Example

### Requirements
- PHP >= 7.4
- Swoole >= 4.5
- Swoole IRC Client >= 0.x

#### Code

```php
class BipBopMyBot implements HandlerInterface {
    private string $twOauthToken;

    public function __construct(string $token) {
        $this->twOauthToken = $token;
    }

    public function onConnect(Client $client): void {
        $client
            ->pass("oauth:{$this->twOauthToken}")
            ->nick('leocavalcantee');
    }

    public function onReply(Reply $reply, Client $client): void {
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
```

#### Starting

```bash
TWITCH_OAUTH_TOKEN=<grab a token at https://twitchapps.com/tmi/> php examples/twitch-bot/bot.ph
```

#### Testing

![Stream chat](screenshot.png)

##### Log
```text
[2020-10-13T22:14:34.951542-03:00] Log.INFO: Connected to irc.chat.twitch.tv at port 6667 [] []
[2020-10-13T22:14:35.040045-03:00] Log.DEBUG: PASS oauth:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX [] []
[2020-10-13T22:14:35.049350-03:00] Log.DEBUG: NICK leocavalcantee [] []
[2020-10-13T22:14:35.289270-03:00] Log.DEBUG: :tmi.twitch.tv 001 leocavalcantee :Welcome, GLHF! [] []
[2020-10-13T22:14:35.300232-03:00] Log.DEBUG: :tmi.twitch.tv 002 leocavalcantee :Your host is tmi.twitch.tv [] []
[2020-10-13T22:14:35.300406-03:00] Log.DEBUG: :tmi.twitch.tv 003 leocavalcantee :This server is rather new [] []
[2020-10-13T22:14:35.300517-03:00] Log.DEBUG: :tmi.twitch.tv 004 leocavalcantee :- [] []
[2020-10-13T22:14:35.300625-03:00] Log.DEBUG: :tmi.twitch.tv 375 leocavalcantee :- [] []
[2020-10-13T22:14:35.300732-03:00] Log.DEBUG: :tmi.twitch.tv 372 leocavalcantee :You are in a maze of twisty passages, all alike. [] []
[2020-10-13T22:14:35.310531-03:00] Log.DEBUG: JOIN #leocavalcantee [] []
[2020-10-13T22:14:35.310712-03:00] Log.DEBUG: :tmi.twitch.tv 376 leocavalcantee :> [] []
[2020-10-13T22:14:35.551791-03:00] Log.DEBUG: :leocavalcantee!leocavalcantee@leocavalcantee.tmi.twitch.tv JOIN #leocavalcantee [] []
[2020-10-13T22:14:35.997148-03:00] Log.DEBUG: :leocavalcantee.tmi.twitch.tv 353 leocavalcantee = #leocavalcantee :leocavalcantee [] []
[2020-10-13T22:14:35.997616-03:00] Log.DEBUG: :leocavalcantee.tmi.twitch.tv 366 leocavalcantee #leocavalcantee :End of /NAMES list [] []
[2020-10-13T22:14:42.351621-03:00] Log.DEBUG: :leocavalcantee!leocavalcantee@leocavalcantee.tmi.twitch.tv PRIVMSG #leocavalcantee :!hello [] []
[2020-10-13T22:14:42.369499-03:00] Log.DEBUG: PRIVMSG #leocavalcantee :Hello, #leocavalcantee! [] []
[2020-10-13T22:18:56.028541-03:00] Log.DEBUG: PING :tmi.twitch.tv [] []
[2020-10-13T22:18:56.028841-03:00] Log.DEBUG: PONG :tmi.twitch.tv [] []
```