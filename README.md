# Swoole IRC Client

💬 [Swoole](https://www.swoole.co.uk/) based [IRC (Internet Relay Chat)](https://tools.ietf.org/html/rfc1459) Client.

## Installation
```bash
composer require leocavalcante/swoole-irc-client
```

## Usage
```php
use SwooleIrc\Client;

$irc = new Client();
$irc->connect($host, $port);
```

### Commands

#### PASS
```php
$irc->pass($password);
```

#### NICK
```php
$irc->nick($nickname);
```