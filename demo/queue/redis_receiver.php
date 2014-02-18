<?php

include_once __DIR__ . '/../include_config.php';

if (!class_exists('Redis')) {
    print <<<TXT
Please install PHP Redis Extension for run this demo (Redis Queue).


TXT;

    exit();
}

use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\Notification\Connection;
use Apple\ApnPush\Queue\Redis;

// Create connection
$connection = new Connection(CERTIFICATE_FILE, PASS_PHRASE, SANDBOX_MODE);

// Create notification
$notification = new Notification($connection);

// Create amqp queue
$amqp = Redis::create($notification);
$amqp->runReceiver();
