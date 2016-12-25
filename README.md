Apple Apn Push
==============

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/53f2239f-c4cc-4643-85c9-a9f79850e863/mini.png)](https://insight.sensiolabs.com/projects/53f2239f-c4cc-4643-85c9-a9f79850e863)
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
use Apple\ApnPush\Model\ApsData;
use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Message;
use Apple\ApnPush\Model\Receiver;
use Apple\ApnPush\Exception\SendMessage\SendMessageException;

// Create certificate and authenticator
$certificate = new Certificate(__DIR__.'/cert.pem', '');
$authenticator = new CertificateAuthenticator($certificate);

// Create builder
$builder = new Http20Builder($authenticator);
$builder->addDefaultVisitors();

// Build sender
$sender = $builder->build();

// Create APS data
$apsData = new ApsData();
$apsData = $apsData->withBody('hi some fail');

// Create message
$message = new Message($apsData);

// Create receiver
$receiver = new Receiver(
    new DeviceToken('6b4d687c1292f1ff05b5653951be4e5f838ce6d39d6b1be1801fe8dcc35713c1'),
    'you.topic.id'
);

try {
    // Send message to receiver
    $sender->send($receiver, $message);
    print "Success send message\n";
} catch (SendMessageException $e) {
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
