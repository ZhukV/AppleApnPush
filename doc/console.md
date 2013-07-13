Command line
============

Your can send messages from console.

Required component:

* [Symfony/Console] (https://github.com/symfony/console)

And you must create console file.

Example:

```php
$autoload = include __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Apple\ApnPush\Command\PushCommand;

$console = new Application;
$console->add(new PushCommand);
// Add other commands
$console->run();
```

Send push notification from console
-----------------------------------

```sh
php console apple:apn-push:send path/to/your/certificate/file.pem DEVICE_TOKEN "Hello world :)"
```

Options:

* --sandbox (null): Use sandbox mode
* --pass-phrase (-p): Pass phrase for your certificate file
* --sound (-s): Use sound key in APS Data
* --badge (-b): Use badge key in APS Data