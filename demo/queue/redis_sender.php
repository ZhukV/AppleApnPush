<?php

include_once __DIR__ . '/include_config.php';

use Apple\ApnPush\Notification;
use Apple\ApnPush\Notification\Message;
use Apple\ApnPush\Queue\Redis;

$autoload = include_once __DIR__ . '/../../vendor/autoload.php';

// Create notification
$notification = new Notification\Notification(CERTIFICATE_FILE);

// Create message
$message = new Message();
$message
    ->setBody('[Redis queue] Hello world')
    ->setDeviceToken(DEVICE_TOKEN);

// Create redis queue
$queue = Redis::create($notification);
$queue->addMessage($message);