<?php

include_once __DIR__ . '/../autoload.php';

if (!class_exists('Symfony\Component\EventDispatcher\EventDispatcher')) {
    \Demo::error('Please install "symfony/event-dispatcher" for run this demo (Notification with EventDispatcher).');
}

use Symfony\Component\EventDispatcher\EventDispatcher;
use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\Notification\NotificationEvents;
use Apple\ApnPush\Notification\SendException;
use Apple\ApnPush\Notification\Connection;

// Create event dispatcher
$eventDispatcher = new EventDispatcher();

// Add complete listener
$eventDispatcher->addListener(NotificationEvents::SEND_MESSAGE_ERROR, function () {
    print "[*] Error with send message.\n";
});

// Add error listener
$eventDispatcher->addListener(NotificationEvents::SEND_MESSAGE_COMPLETE, function () {
    print "[-] Complete send message.\n";
});

// Create connection
$certificate = new Certificate(CERTIFICATE_FILE, PASS_PHRASE);
$connection = new Connection($certificate, SANDBOX_MODE);

// Create notification
$notification = new Notification($connection);

// Set event dispatcher
$notification->setEventDispatcher($eventDispatcher);

// Send correct message
$notification->sendMessage(DEVICE_TOKEN, '[Correct] Hello');

try {
    // Send incorrect message
    $notification->sendMessage(str_repeat('a', 64), '[Incorrect] Hello');
} catch (SendException $e) {
}
