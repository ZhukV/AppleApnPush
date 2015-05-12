<?php

namespace {
    use Psr\Log\AbstractLogger;

    /**
     * Custom logger
     */
    class CustomLogger extends AbstractLogger
    {
        public function log($level, $message, array $context = array())
        {
            print sprintf("%s: %s %s\n", $level, $message, json_encode($context));
        }
    }
}
