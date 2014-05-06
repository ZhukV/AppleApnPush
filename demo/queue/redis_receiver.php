<?php

include_once __DIR__ . '/../autoload.php';

if (!class_exists('Redis')) {
    \Demo::error('Please install PHP Redis Extension for run this demo (Redis Queue).');
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
