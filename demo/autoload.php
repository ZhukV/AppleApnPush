<?php

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
