<?php

declare(strict_types=1);

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Demo\Boot;

class Demo
{
    /**
     * Demo bootstrap
     */
    public static function boot()
    {
        self::checkVendors();
        include_once self::getVendorDirectory().'/autoload.php';
        self::includeConfigurationFile();
    }

    /**
     * Get vendor directory
     *
     * @return string
     */
    private static function getVendorDirectory(): string
    {
        return __DIR__.'/../../vendor';
    }

    /**
     * Check if vendors is installed
     */
    private static function checkVendors()
    {
        $autoloadFile = self::getVendorDirectory().'/autoload.php';

        if (!file_exists($autoloadFile)) {
            print sprintf(
                'The required packages not installed. Please download composer.phar and run install or update command.%s',
                PHP_EOL
            );

            exit(1);
        }
    }

    /**
     * Include configuration file with constants
     */
    private static function includeConfigurationFile()
    {
        static $included = false;

        if ($included) {
            // The configuration file already included
            return;
        }

        $file = __DIR__.'/config.php';

        if (!file_exists($file)) {
            print sprintf(
                'The configuration file "%s" was not found.%sPlease copy %s/config.php.dist to %s/config.php and modify new file for set own parameters.%s',
                $file,
                PHP_EOL,
                __DIR__,
                __DIR__,
                PHP_EOL
            );

            exit(1);
        }

        $included = true;
        include_once $file;
    }
}
