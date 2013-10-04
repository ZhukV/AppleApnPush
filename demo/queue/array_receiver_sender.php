<?php

include_once __DIR__ . '/include_config.php';

include_once __DIR__ . '/../../vendor/autoload.php';

use Apple\ApnPush\Notification;
use Apple\ApnPush\Notification\Message;
use Apple\ApnPush\Queue;
use Apple\ApnPush\Queue\Adapter\ArrayAdapter;

// Create array adapter
$adapter = new ArrayAdapter();

// Create notification
$notification = new Notification(CERTIFICATE_FILE);

// Create queue
$queue = new Queue($adapter, $notification);

// Add messages
$queue->addMessage(new Message(DEVICE_TOKEN, 'Hello world 1'));
$queue->addMessage(new Message(DEVICE_TOKEN, 'Hello world 2'));
$queue->addMessage(new Message(DEVICE_TOKEN, 'Hello world 3'));

// Run receiver for send all messages
$queue->runReceiver();
