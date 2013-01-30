Apple APN Push notifications
============================

This package has components:

* Message (Control message APS and custom data)
* Notification (Send messages)
* Connection (For connection to Apple servers)
* PayloadFactory (For creating payload hash)

Default usage:
1. Initialize connection
2. Initialize payload factory
3. Initialize notification core
4. Set connection and payload to notification system

Default example:
```php
use Apple\ApnPush\Connection\Connection;
use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\PayloadFactory\PayloadFactory;

// Create connection from constructor
$certificateFile = 'test.pem';
$sandboxMode = TRUE;
$certificatePassPhrase = '';
$connection = new Connection($certificateFile, $certificatePassPhrase, $sandboxMode);

// Create conneciton from setters
$connection = new Connection();
$connection->setCertificateFile('test.pem');
$connection->setCertificatePassPhrase('foo');
$connection->setSandboxMode(TRUE);

// Create default payload factory
$payloadFactory = new PayloadFactory;

// Create notification system
$notification = new Notification;

// Set connection to notification system
$notification->setConnection($connection);
// Set payload factory
$notification->setPayloadFactory($payloadFactory);
```

Each message must be instance `Apple\ApnPush\Messages\MessageInterface`
Example send message:

```php
use Apple\ApnPush\Messages\DefaultMessage;

// Create message
$message = new DefaultMessage;

// Set body
$message->setBody('Привет!!');
// Set message identifier
$message->setIdentifier('123456789');
// Set device token (REQUIRED)
$message->setDeviceToken('TOKEN');

// Set message
$notification->sendMessage($message);
```

#### ATTENTION:
Device token must be patter of template: `/[0-9a-f]]/` and size must be 64 charset.

Logger
------

Your can control send messages with logger (`Psr\Log\LoggerInterface`)

Example:

```php
// Create monolog handler
$logger = new Monolog\Logger('apple.apn_push');
$logger->pushHandler(new Monolog\Handler\ChromePHPHandler());

$notification->setLogger($logger);
```

Control errors
--------------

Example control error push notification:

```php
use Apple\apnPush\Notification\SendExceptionInterface;

try {
    $notification->sendMessage($message);
    print 'Success send message';
}
catch (SendExceptionInterface $error) {
    print (string) $error;
    // Your logic
}
```