<?php declare(strict_types=1);

namespace SwooleIrc\Test;

use SwooleIrc\CallbackHandler;
use SwooleIrc\Client;
use SwooleIrc\Reply;

it('handles the client using callbacks', function () {
    $reply_message = null;

    $cb = function (Reply $reply) use (&$reply_message): void {
        $reply_message = $reply->message;
    };

    Client::withHandler(CallbackHandler::reply($cb))
        ->handleMessage(':test 123 test');

    $this->assertSame(':test 123 test', $reply_message);
});