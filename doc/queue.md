Queue
=====

You can use queue system for send push notifications.

Queue system get message from adapter.

> **Note:** queue system can be start as high-load with use "server - node" system.

Available:

* Amqp
* Redis
* Simple array - This adapter can't start for "server-node" system


For start receiver node, you must configure adapter, notification and create queue instance.

Example (With simple array):

```php
use Apple\ApnPush\Notification;
use Apple\ApnPush\Queue;
use Apple\ApnPush\Queue\Adapter\ArrayAdapter;

// Create array adapter
$adapter = new ArrayAdapter();

// Create notification
$notification = new Notification('/path/to/you/certificate.pem');

// Create queue
$queue = new Queue($adapter, $notification);
```

After create queue instance you can send message to queue or start receiver node.

```php
// Add message to queue
$queue->addMessage(new Message($deviceToken, 'Hello world 1'));
$queue->addMessage(new Message($deviceToken, 'Hello world 2'));
$queue->addMessage(new Message($deviceToken, 'Hello world 3'));

// Run receiver
$queue->runReceiver();
```

Set custom notification error handler:

```php
$handler = function (SendException $e) {
    print (string) $e;
};

$queue->setNotificationErrorHandler($handler);
$queue->runReceiver();
```
