Apple ApnPush Command
=====================

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

* --sandbox (NULL): Use sandbox mode
* --pass-phrase (-p): Pass phrase for your certificate file
* --sound (-s): Use sound key in APS Data
* --badge (-b): Use badge key in APS Data