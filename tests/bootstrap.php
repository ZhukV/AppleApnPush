<?php

$vendorDir = __DIR__ . '/../../..';

if (file_exists($file = $vendorDir . '/autoload.php')) {
    $loader = require_once $file;
} else if (file_exists($file = './vendor/autoload.php')) {
    require_once $file;
} else {
    throw new \RuntimeException("Not found composer autoload");
}

// Include common classes
if (class_exists('\AMQPConnection', false)) {
    require_once __DIR__ . '/Queue/Adapter/AmqpQueueMock.php';
    require_once __DIR__ . '/Queue/Adapter/AmqpExchangeMock.php';
} else {
    // Define required constants for AMQP.
    // If we not defined this constants, then the test still be failed, because
    // AmqpTest.php use this constants
    define ('AMQP_NOPARAM', 0);
    define ('AMQP_IMMEDIATE', 0);
}