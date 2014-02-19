<?php

namespace Demo;

use Psr\Log\AbstractLogger;

/**
 * Override abstract logger for write records to console
 */
class CustomLogger extends AbstractLogger
{
    public function log($level, $message, array $context = array())
    {
        print sprintf("%s: %s %s\n", $level, $message, json_encode($context));
    }
}
