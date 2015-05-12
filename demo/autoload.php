<?php

namespace {
    /**
     * Common functions for demo environment
     */
    class Demo
    {
        /**
         * Find and include composer autoload if exists
         * @return bool|mixed
         */
        public static function autoload()
        {
            if (!file_exists($file = __DIR__ . '/../vendor/autoload.php')) {
                if (!file_exists($file = __DIR__ . '/../../../vendor/autoload.php')) {
                    static::error(array(
                        "Autoload file not found (Composer autoload).",
                        "Please install composer.phar and run update or install command."
                    ));

                    return false;
                }
            }

            return include_once $file;
        }

        /**
         * Control stop exception
         */
        public static function registerExceptionHandler()
        {
            set_exception_handler(function ($exception) {
                if (!$exception instanceof StopException) {
                    throw $exception;
                }
            });
        }

        /**
         * Include configuration file
         */
        public static function includeConfig()
        {
            if (!is_file(__DIR__ . '/config.php')) {
                static::error(array(
                    "Please create a config.php file in demo directory (~/demo/config.php) with content:",
                    "",
                    "<?php",
                    "",
                    "define ('CERTIFICATE_FILE', 'Path to your certificate file');",

                    "define ('PASS_PHRASE', null); // If you generate certificate file with pass phrase",
                    "define ('DEVICE_TOKEN', 'Test token'); // Must be a pattern: /^[a-f0-9]{64}$/",
                    "define ('SANDBOX_MODE', true); // Use sandbox mode? true - yes, false - no"
                ));
            }
        }

        /**
         * Exit from demo process
         */
        public static function error($message)
        {
            if (is_array($message)) {
                $message = implode("\n", $message);
            }

            print trim($message) . "\n\n";

            throw new \StopException;
        }

        /**
         * Include common files
         */
        public static function includeCommonFiles()
        {
            include_once __DIR__ . '/StopException.php';
        }

        /**
         * Boot demo environment
         */
        public static function boot()
        {
            Demo::includeCommonFiles();
            Demo::registerExceptionHandler();
            Demo::includeConfig();
            Demo::autoload();
        }
    }

    \Demo::boot();
}
