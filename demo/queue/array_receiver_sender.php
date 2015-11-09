<?php

include_once __DIR__ . '/../autoload.php';

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\Notification\Connection;
use Apple\ApnPush\Notification\Message;
use Apple\ApnPush\Queue;
use Apple\ApnPush\Queue\Adapter\ArrayAdapter;

// Create array adapter
$adapter = new ArrayAdapter();

// Create connection
$certificate = new Certificate(CERTIFICATE_FILE, PASS_PHRASE);
$connection = new Connection($certificate, SANDBOX_MODE);

// Create notification
$notification = new Notification($connection);

// Create queue
$queue = new Queue($adapter, $notification);

// Add messages
$queue->addMessage(new Message(DEVICE_TOKEN, 'Hello world 1'));
$queue->addMessage(new Message(DEVICE_TOKEN, 'Hello world 2'));
$queue->addMessage(new Message(DEVICE_TOKEN, 'Hello world 3'));

// Run receiver for send all messages
$queue->runReceiver();
