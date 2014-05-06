<?php

include_once __DIR__ . '/../autoload.php';

if (!class_exists('AMQPConnection')) {
    \Demo::error('Please install PHP Amqp Extension for run this demo (AMQP Queue).');
}

use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\Queue\Amqp;
use Apple\ApnPush\Notification\Connection;

// Create connection
$connection = new Connection(CERTIFICATE_FILE, PASS_PHRASE, SANDBOX_MODE);

// Create notification
$notification = new Notification($connection);

// Create amqp queue
$amqp = Amqp::create($notification);
$amqp->runReceiver();
