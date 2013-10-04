<?php

if (!is_file(__DIR__ . '/config.php')) {
    print <<<TXT
Please create a config.php in demo directory with content:

<?php

define ('CERTIFICATE_FILE', 'Path to your certificate file');
define ('DEVICE_TOKEN', 'Test token');



TXT;

    exit();
}

include_once __DIR__ . '/config.php';