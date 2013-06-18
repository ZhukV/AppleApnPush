Apple Push Notifications
========================

[![Build Status](https://travis-ci.org/ZhukV/AppleApnPush.png?branch=master)](https://travis-ci.org/ZhukV/AppleApnPush)

[Issues] (https://github.com/ZhukV/AppleApnPush/issues)


This package has components:

* Message (Control message APS and custom data)
* Notification (Send messages)
* Connection (For connection to Apple servers)
* PayloadFactory (For creating payload hash)

Default usage:
* Initialize connection
* Initialize payload factory
* Initialize notification core
* Set connection and payload to notification system


Default example:
```php
use Apple\ApnPush\Connection\Connection;
use Apple\ApnPush\Notification\Notification;
use Apple\ApnPush\PayloadFactory\PayloadFactory;

// Create connection from constructor
$certificateFile = 'test.pem';
$sandboxMode = true;
$certificatePassPhrase = '';
$connection = new Connection($certificateFile, $certificatePassPhrase, $sandboxMode);

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
$message->setBody('Hello world!');
// Set message identifier
$message->setIdentifier(123);
// Set device token (REQUIRED)
$message->setDeviceToken('TOKEN');

// Set message
$notification->sendMessage($message);
```

#### ATTENTION:
Device token must be patter of template: `/[0-9a-f]]/` and size must be 64 charset.

Message system
--------------

Your can create own custom message for push notification
Example:

```php
use Apple\ApnPush\Messages\DefaultMessage;

class MyMessage extends DefaultMessage
{
    /**
     * @var object
     */
    protected $object;

    /**
     * Construct
     */
    public function __construct($myObject)
    {
        $this->object = $myObject;
    }

    /**
     * This method called in each create payload data
     * for generate aps data
     */
    protected function preparePayload()
    {
        $this->addCustomData('objectId', $this->object->id);
    }
}

$object = new stdClass;
$object->id = 123;
$message = new MyMessage($object);

// Aps data with this message
array(
    aps => array(/** .... **/),
    objectId => 123
);
```

Logger
------

Your can control send messages with logger (`Psr\Log\LoggerInterface` and `Monolog`)

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
} catch (SendExceptionInterface $error) {
    print (string) $error;
    // Your logic
}
```

After each error with send push notification, connection reopened.

Example send from list `array`

```php
use Apple\apnPush\Notification\SendExceptionInterface;

$messages = array(
    // ... Messages
);

foreach ($messages as $message) {
    try {
        $notification->sendMessage($message);
        print "Success send message...\n";
    } catch (SendExceptionInterface $error) {
        print (string) $error . "\n";
        // Connection auto reopen before next sending
    }
}
```

Usage feedback service
======================

Example get invalid devices:

```php
use Apple\ApnPush\Feedback\Service;
use Apple\ApnPush\Connection\Feedback;

// Create new feedback connection (Disable write and control ready read)
$connection = new Feedback('/apn_push.pem');

// Create feedback service
$feedBack = new Service($connection);

$invalidDevices = $feedBack->getInvalidDevices();
```

Usage command with Push Notification
====================================

Your can send messages from console.

Required component:

* [Symfony/Console] (https://github.com/symfony/console)

#### Step 1 (Create console file) `/path/to/your/app/console`:
```php
$autoload = include __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Apple\ApnPush\Command\PushCommand;

$console = new Application;
$console->add(new PushCommand);
// Add other commands
$console->run();
```

#### Step 2 (Send push):
```sh
php console apple:apn-push:send path/to/your/certificate/file.pem DEVICE_TOKEN "Message"
```

Options:

* --sandbox (null): Use sandbox mode
* --pass-phrase (-p): Pass phrase for your certificate file
* --sound (-s): Use sound key in APS Data
* --badge (-b): Use badge key in APS Data