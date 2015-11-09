<?php

include_once __DIR__ . '/../autoload.php';

if (!class_exists('Redis')) {
    \Demo::error('Please install PHP Redis Extension for run this demo (Redis Queue).');
}

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\Notification\Message;
use Apple\ApnPush\Notification\Connection;
use Apple\ApnPush\Queue\Redis;

// Create connection
$certificate = new Certificate(CERTIFICATE_FILE, PASS_PHRASE);
$connection = new Connection($certificate, SANDBOX_MODE);

// Create notification
$notification = new Notification($connection);

// Create message
$message = new Message();
$message
    ->setBody('[Redis queue] Hello world')
    ->setDeviceToken(DEVICE_TOKEN);

// Create redis queue
$queue = Redis::create($notification);
$queue->addMessage($message);
