<?php

/*
 * The example for send notification to device with authenticate via ceritificate
 */

namespace Acme\Demo;

include_once __DIR__.'/boot/bootstrap.php';

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Protocol\Http\Authenticator\CertificateAuthenticator;
use Apple\ApnPush\Sender\Builder\Http20Builder;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Receiver;
use Apple\ApnPush\Exception\SendNotification\SendNotificationException;

// Create authenticator system
$certificate = new Certificate(CERTIFICATE_PATH, CERTIFICATE_PASS_PHRASE);
$authenticator = new CertificateAuthenticator($certificate);

// Create sender with builder
$builder = new Http20Builder($authenticator);
$builder->addDefaultVisitors();

$sender = $builder->build();

// Create alert, aps, payload, and notification for send to device
$payload = Payload::createWithBody('Hello ;) Send notification with certificate authentication.');
$notification = new Notification($payload);

$receiver = new Receiver(
    new DeviceToken(DEVICE_TOKEN),
    APNS_TOPIC
);

try {
    $sender->send($receiver, $notification);

    print 'Oh... ;) Success send notification!'.PHP_EOL;
} catch (SendNotificationException $e) {
    print 'Oops... Fail send message: '.$e->getMessage().PHP_EOL;
}
