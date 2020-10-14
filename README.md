# Swoole IRC Client

💬 [Swoole](https://www.swoole.co.uk/) based [IRC (Internet Relay Chat)](https://tools.ietf.org/html/rfc1459) Client.

## Installation
```bash
composer require leocavalcante/swoole-irc-client
```

## Usage
```php
use SwooleIrc\{HandlerInterface, Reply, Client};

class MyHandler implements HandlerInterface {
    public function onConnect(Client $irc): void {}
    public function onReply(Reply $reply, Client $irc): void {}
}

$irc = Client::withHandler(new MyHandler());
$irc->connect($host, $port);
$irc->start();
```

### CallbackHandler

This library provides a convenient way to pass a regular callable as well if you don't want to create a class and implement an interface.

```php
use SwooleIrc\{Reply, Client, CallbackHandler};

$handler = static function (Reply $reply): void {};

$irc = Client::withHandler(CallbackHandler::reply($handler))
    ->connect($host, $port)
    ->start();
```

## Commands

### PASS
```php
$irc->pass($password);
```

### NICK
```php
$irc->nick($nickname);
```

### JOIN
```php
$irc->join([$channel]);
$irc->join([$channel], [$key]);
```

### PART
```php
$irc->part([$channel]);
```

### PRIVMSG
```php
$irc->privmsg([$channel], $text);
```

---

Please, for now, take a look at the source code to see all supported commands.

And you can always implement `MessageInterface` to send your own messages thought `$irc->send(MessageInterface $message)`
or send raw lines with `$irc->writeln(string $raw)`.