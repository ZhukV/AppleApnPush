<?php

include_once __DIR__ . '/include_config.php';

use Apple\ApnPush\Notification;
use Apple\ApnPush\Queue\Amqp;

$autoload = include_once __DIR__ . '/../../vendor/autoload.php';

// Create notification
$notification = new Notification(CERTIFICATE_FILE);

// Create amqp queue
$amqp = Amqp::create($notification);
$amqp->runReceiver();

