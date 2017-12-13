Apple Apn Push
==============

Now, we support only HTTP protocol. If you cannot use HTTP protocol and want use binary protocol, please
use version 2.* of this package.

Creating sender
---------------

Before send the notification to iOS devices you must create the sender with protocol.

For easy creating sender, you can use builder:

```php
<?php

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Protocol\Http\Authenticator\CertificateAuthenticator;
use Apple\ApnPush\Sender\Builder\Http20Builder;

// Create certificate and authenticator
$certificate = new Certificate('/path/to/you/certificate.pem', 'pass phrase');
$authenticator = new CertificateAuthenticator($certificate);

// Build sender
$builder = new Http20Builder($authenticator);
$builder->addDefaultVisitors();

$sender = $builder->build();
```

> **Attention:** visitors are required for adding the headers to HTTP request (apns-id, apns-collapse-id, etc...).

We support the JSON Web Token authentication, and if you want use JWT, please create `JwtAuthenticator`:

> **Attention:** For use JWT, the package **spomky-labs/jose** must be installed.

```php
<?php

use Apple\ApnPush\Jwt\Jwt;
use Apple\ApnPush\Protocol\Http\Authenticator\JwtAuthenticator;

$jwt = new Jwt('team id', 'key', '/path/to/you/certificate.p8');
$authenticator = new JwtAuthenticator($jwt);

```

### Access to connection

In many cases, you should close the connection after sending the push notification. As an example: you send the 
notifications on background worker.

You can close connections from protocol. But, you should previously create the protocol from builder (or manually) and
manually create the sender.

```php
<?php

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Protocol\Http\Authenticator\CertificateAuthenticator;
use Apple\ApnPush\Sender\Builder\Http20Builder;
use Apple\ApnPush\Sender\Sender;

// Create certificate and authenticator
$certificate = new Certificate('/path/to/you/certificate.pem', 'pass phrase');
$authenticator = new CertificateAuthenticator($certificate);

// Build sender
$builder = new Http20Builder($authenticator);
$builder->addDefaultVisitors();

$protocol = $builder->buildProtocol();
$sender = new Sender($protocol);

// some actions/send push notifications

$protocol->closeConnection();

// some actions
```


Sending notifications
---------------------

After success creating the sender, you can send the notification to any receiver.
Before send, you must create the notification object and receiver object.

```php
<?php

use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Receiver;

$notification = Notification::createWithBody('Hello ;)');
$receiver = new Receiver(new DeviceToken('device token'), 'you.app.bundle_id');

$sender->send($receiver, $notification);
```

The `send` method return `void` type (nothing). If we have the problem with send notification (any error), this method
throw `SendNotificationException`.

```php
<?php

use Apple\ApnPush\Exception\SendNotification\SendNotificationException;

try {
    $sender->send($receiver, $notification);
    // Success send notification.
} catch (SendNotificationException $e) {
    // Fail send notification.    
}
``` 

Creating custom notification
----------------------------

In many issues you must create the notification with custom sound or custom budge, etc...
**We support all attributes of notification!**

The root of notification slit to next objects:

* Notification - the root object of notification. Store payload, priority, expiration, apns-id, collapse-id.
* Payload - the payload of notification. Store aps data and custom data.
* Aps - the aps of notification. Store alert, badge, sound, category, thread, content-available.
* Alert - the alert of notification. Store title, body, launch image and localized data.

> **Attention:** All object is immutable and you can not modify their. After any modification you receive new instance.

```php
<?php

use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Expiration;
use Apple\ApnPush\Model\Priority;
use Apple\ApnPush\Model\ApnId;
use Apple\ApnPush\Model\CollapseId;

$alert = (new Alert())
    ->withBody('Hello ;)')
    ->withTitle('Hi ;)')
    ->withLaunchImage('push.png');

$aps = (new Aps($alert))
    ->withBadge(2)
    ->withSound('pong.acc')
    ->withThreadId('my.app.thread')
    ->withContentAvailable(true);

$payload = (new Payload($aps))
    ->withCustomData('key1', 'value1')
    ->withCustomData('key2', ['some' => 'foo-bar']);

$notification = (new Notification($payload))
    ->withExpiration(new Expiration(new \DateTime('+1 day')))
    ->withPriority(Priority::immediately())
    ->withApnId(new ApnId('550e8400-e29b-41d4-a716-446655440000'))
    ->withCollapseId(new CollapseId('some-foo-bar'));

$sender->send($receiver, $notification);
```
