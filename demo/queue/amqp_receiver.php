<?php

include_once __DIR__ . '/../include_config.php';

if (!class_exists('AMQPConnection')) {
    print <<<TXT
Please install PHP Amqp Extension for run this demo (AMQP Queue).


TXT;

    exit();
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

