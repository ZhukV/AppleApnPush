Apple Apn Push
==============

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/53f2239f-c4cc-4643-85c9-a9f79850e863/mini.png)](https://insight.sensiolabs.com/projects/53f2239f-c4cc-4643-85c9-a9f79850e863)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ZhukV/AppleApnPush/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ZhukV/AppleApnPush/?branch=master)
[![Build Status](https://travis-ci.org/ZhukV/AppleApnPush.svg?branch=master)](https://travis-ci.org/ZhukV/AppleApnPush)

Send push notifications to apple devices (iPhone, iPad, iPod).

Requirements
------------

Now library work only with HTTP/2 protocol, and next libraries is necessary:

* [cURL](http://php.net/manual/ru/book.curl.php)
* The protocol [HTTP/2](https://en.wikipedia.org/wiki/HTTP/2) must be supported in cURL.

Installation
------------

Add AppleApnPush in your composer.json:

````json
{
    "require": {
        "apple/apn-push": "~3.0.0"
    }
}
````

Now tell composer to download the library by running the command:

```bash
$ php composer.phar update apple/apn-push
```

Easy usage
----------

You can use builder for create sender for next send push notifications to device:

```php
<?php

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Protocol\Http\Authenticator\CertificateAuthenticator;
use Apple\ApnPush\Sender\Builder\Http20Builder;
use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Receiver;
use Apple\ApnPush\Exception\SendNotification\SendNotificationException;

// Create certificate and authenticator
$certificate = new Certificate(__DIR__.'/cert.pem', '');
$authenticator = new CertificateAuthenticator($certificate);

// Build sender
$builder = new Http20Builder($authenticator);
$builder->addDefaultVisitors();

$sender = $builder->build();

// Create payload
$alert = new Alert('hello ;)');
$aps = new Aps($alert);
$payload = new Payload($aps);

// Create notification
$notification = new Notification($payload);

$receiver = new Receiver(
    new DeviceToken('6b4d687c1292f1ff05b5653951be4e5f838ce6d39d6b1be1801fe8dcc35713c9'),
    'you.app.id'
);

try {
    $sender->send($receiver, $notification);
} catch (SendNotificationException $e) {
    print 'Fail send message: '.$e->getMessage()."\n";
}

```


License
-------

This library is under the MIT license. See the complete license in library

```
LICENSE
```

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/ZhukV/AppleApnPush/issues).

Contributors:
-------------

Thanks to [everyone participating] (https://github.com/ZhukV/AppleApnPush/graphs/contributors) in the development of this AppleApnPush library!

* Ryan Martinsen [popthestack] (https://github.com/popthestack)
