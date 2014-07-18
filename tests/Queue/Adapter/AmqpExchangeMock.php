<?php

namespace Apple\ApnPush\Queue\Adapter;

/**
 * Override base AMQPExchange class, because PHPUnit >= 4.* can mock
 * __construct method in internal class
 *
 * @author jbboehr <jbboehr@gmail.com>
 */
class AmqpExchangeMock extends \AMQPExchange
{
    /**
     * Construct
     */
    public function __construct()
    {
    }
}