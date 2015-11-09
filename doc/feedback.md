Apple Apn Push Feedback
=======================

You can get invalid device tokens with using this service.

Default example (Set certificate file from constructor):

```php
use Apple\ApnPush\Feedback;

$feedback = new Feedback('/path/to/your/certificate.pem');
print_r($feedback->getInvalidDevices());
```

Or create feedback connection:

```php
use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Feedback\Feedback;
use Apple\ApnPush\Feedback\Connection;

$certificate = new Certificate('/path/to/your/certificate.pem', 'pass_phrase');
// Second parameter - sandbox mode
$connection = new Connection($certificate, false);
$feedback = new Feedback($connection);
print_r($feedback->getInvalidDevices());
```
