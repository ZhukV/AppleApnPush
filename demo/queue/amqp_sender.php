<?php

include_once __DIR__ . '/../include_config.php';

if (!class_exists('AMQPConnection')) {
    print <<<TXT
Please install PHP Amqp Extension for run this demo (AMQP Queue).


TXT;

    exit();
}

use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\Notification\Message;
use Apple\ApnPush\Notification\Connection;
use Apple\ApnPush\Queue\Amqp;

// Create connection
$connection = new Connection(CERTIFICATE_FILE, PASS_PHRASE, SANDBOX_MODE);

// Create notification
$notification = new Notification($connection);

// Create message
$message = new Message();
$message
    ->setBody('[Amqp queue] Hello world')
    ->setDeviceToken(DEVICE_TOKEN);

// Create amqp queue and send message
$amqp = Amqp::create($notification);
$amqp->addMessage($message);
