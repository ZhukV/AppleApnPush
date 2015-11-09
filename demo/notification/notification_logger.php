<?php

include_once __DIR__ . '/../autoload.php';

if (!interface_exists('Psr\Log\LoggerInterface')) {
    \Demo::error('Please install "psr/log" for run this demo (Notification with logger system).');
}

include_once __DIR__ . '/CustomLogger.php';

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\Notification\Connection;
use Apple\ApnPush\Notification\SendException;

// Create connection
$certificate = new Certificate(CERTIFICATE_FILE, PASS_PHRASE);
$connection = new Connection($certificate, SANDBOX_MODE);

// Create custom logger
$logger = new CustomLogger();

// Create notification
$notification = new Notification();
$notification
    ->setLogger($logger)
    ->setConnection($connection);

// Send to correct device token
$notification->sendMessage(DEVICE_TOKEN, '[Correct] Hello');

try {
    // Send to incorrect device token
    $notification->sendMessage(str_repeat('a', 64), '[Incorrect] Hello');
} catch (SendException $e) {
}

$notification->sendMessage(DEVICE_TOKEN, '[Correct] Hello 2 ;)');
