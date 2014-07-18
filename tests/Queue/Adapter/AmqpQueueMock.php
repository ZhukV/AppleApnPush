<?php

namespace Apple\ApnPush\Queue\Adapter;

/**
 * Override base AMQPQueue class, because PHPUnit >= 4.* can mock
 * __construct method in internal class
 *
 * @author jbboehr <jbboehr@gmail.com>
 */
class AmqpQueueMock extends \AMQPQueue
{
    /**
     * Construct
     */
    public function __construct()
    {
    }
}