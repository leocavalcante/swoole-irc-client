<?php declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Swoole\Coroutine as Co;
use SwooleIrc\CallbackHandler;
use SwooleIrc\Client;
use SwooleIrc\Reply;

Co\run(static function (): void {
    $on_message = static function (Reply $reply): void {
        echo $reply->message, PHP_EOL;
    };

    $client = Client::withHandler(CallbackHandler::reply($on_message))
        ->connect('chat.freenode.net', 6667);

    $client->start();

    while (true) {
        $ln = trim(fgets(STDIN));

        if ($ln === '/q') {
            $client->quit();
            Co::suspend();
        }

        $client->writeln($ln);
    }
});

