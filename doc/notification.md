Apple Apn Push Notification
===========================

For send push notification your must create notification service:

Default example (Set certificate file from constructor):

```php
use Apple\ApnPush\Notification;

$notification = new Notification('/path/to/your/certificate.pem');
```

Create notification with connection object:

```php
use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Notification;
use Apple\ApnPush\Notification\Connection;

$certificate = new Certificate('/path/to/your/certificate.pem', 'your_passphrase');
// Second argument - sandbox mode
$connection = new Connection($certificate, false);
$notification = new Notification($connection);
```

After create notification service your can send push message to apple devices.

Base example:

```php
$notification->sendMessage('device_token', 'Hello world');
```

Or create custom message and send message:

```php
use Apple\ApnPush\Notification\Message;

// Create message
$message = new Message;

// Set body
$message->setBody('Hello world!');
// Set message identifier
$message->setIdentifier(123);
// Set device token (REQUIRED)
$message->setDeviceToken('TOKEN');

// Set message
$notification->send($message);
```

Send messages from list array:

```php
use Apple\apnPush\Notification\SendException;

$messages = array(
    // ... Messages
);

foreach ($messages as $message) {
    try {
        $notification->send($message);
        print "Success send message...\n";
    } catch (SendException $error) {
        print (string) $error . "\n";
        // Connection auto reopen before next sending
    }
}
```

#### ATTENTION:
Device token must be patter of template: `/[0-9a-f]]/` and size must be 64 charset.

Message system
--------------

Your can create own custom message for push notification.
Example:

```php
use Apple\ApnPush\Notification\Message;

class MyMessage extends Message
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

Control errors
--------------

Example control error push notification:

```php
use Apple\apnPush\Notification\SendException;

try {
    $notification->sendMessage($message);
    print 'Success send message';
} catch (SendException $error) {
    print (string) $error;
    // Your logic
}
```

After each error with send push notification, connection reopened.

Logger
------

Your can use `\Psr\Logger\LoggerInterface` for control actions in notification service.

Example set logger (`\Monolog\Monolog`):

```php
// Create monolog handler
$logger = new Monolog\Logger('apple.apn_push');
$logger->pushHandler(new Monolog\Handler\ChromePHPHandler());

$notification->setLogger($logger);
```

EventDispatcher
---------------

You can use event system for control complete/error send message.

Example:

```php
use Apple\ApnPush\Notification\NotificationEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

// Create event dispatcher
$eventDispatcher = new EventDispatcher;

// Add complete listener
$eventDispatcher->addListener(NotificationEvents::SEND_MESSAGE_ERROR, function (){
    print "[*] Error with send message.\n";
});

// Add error listener
$eventDispatcher->addListener(NotificationEvents::SEND_MESSAGE_COMPLETE, function (){
    print "[-] Complete send message.\n";
});

$notification->setEventDispatcher($eventDispatcher);

All event names defined in `Apple\ApnPush\Notification\NotificationEvents`