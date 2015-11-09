<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Queue\Adapter;

use Apple\ApnPush\Notification\Message;

class ArrayAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic test
     */
    public function testBase()
    {
        $adapter = new ArrayAdapter();

        $this->assertCount(0, $adapter);
        $this->assertNull($adapter->getMessage());
        $adapter->addMessage(new Message());
        $this->assertCount(1, $adapter);
        $adapter->addMessage(new Message());
        $this->assertCount(2, $adapter);

        $message = $adapter->getMessage();
        $this->assertInstanceOf('Apple\ApnPush\Notification\Message', $message);
        $this->assertCount(1, $adapter);
        $message = $adapter->getMessage();
        $this->assertInstanceOf('Apple\ApnPush\Notification\Message', $message);
        $this->assertCount(0, $adapter);
        $this->assertNull($adapter->getMessage());
    }
}