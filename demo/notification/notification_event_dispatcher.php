<?php

include_once __DIR__ . '/../include_config.php';

if (!class_exists('Symfony\Component\EventDispatcher\EventDispatcher')) {
    print <<<TXT
Please install "symfony/event-dispatcher" for run this demo (Notification with EventDispatcher).


TXT;

    exit();
}

use Symfony\Component\EventDispatcher\EventDispatcher;
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
$connection = new Connection(CERTIFICATE_FILE, PASS_PHRASE, SANDBOX_MODE);

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
