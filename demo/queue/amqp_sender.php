<?php

include_once __DIR__ . '/../autoload.php';

if (!class_exists('AMQPConnection')) {
    \Demo::error('Please install PHP Amqp Extension for run this demo (AMQP Queue).');
}

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\Notification\Message;
use Apple\ApnPush\Notification\Connection;
use Apple\ApnPush\Queue\Amqp;

// Create connection
$certificate = new Certificate(CERTIFICATE_FILE, PASS_PHRASE);
$connection = new Connection($certificate, SANDBOX_MODE);

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
