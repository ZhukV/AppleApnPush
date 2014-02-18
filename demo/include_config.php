<?php

if (!is_file(__DIR__ . '/config.php')) {
    print <<<CONFIG
Please create a config.php file in demo directory (~/demo/config.php) with content:

<?php

define ('CERTIFICATE_FILE', 'Path to your certificate file');
define ('PASS_PHRASE', null); // If you generate certificate file with pass phrase
define ('DEVICE_TOKEN', 'Test token'); // Must be a pattern: /^[a-f0-9]{64}$/
define ('SANDBOX_MODE', true); // Use sandbox mode? true - yes, false - no



CONFIG;

    exit();
}

include_once __DIR__ . '/config.php';
include_once __DIR__ . '/autoload.php';

// Run autoload
_autoload();
