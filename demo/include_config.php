<?php

if (!is_file(__DIR__ . '/config.php')) {
    print <<<TXT
Please create a config.php file in demo directory (~/demo/config.php) with content:

<?php

define ('CERTIFICATE_FILE', 'Path to your certificate file');
define ('PASS_PHRASE', null); // If you generate certificate file with pass phrase
define ('DEVICE_TOKEN', 'Test token'); // Must be a pattern: /^[a-f0-9]{64}$/
define ('SANDBOX_MODE', true); // Use sandbox mode? true - yes, false - no



TXT;

    exit();
}

include_once __DIR__ . '/config.php';

/**
 * Search composer autoload
 */
function _autoload()
{
    if (!file_exists($file = __DIR__ . '/../vendor/autoload.php')) {
        if (!file_exists($file = __DIR__ . '/../../../vendor/autoload.php')) {
            print <<<TXT
Autoload file not found (Composer autoload).
Please install composer.phar and run update or install command.


TXT;

            exit();
        }
    }

    return include_once $file;
}

// Run autoload
_autoload();