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

class RedisTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Redis|\PHPUnit_Framework_MockObject_MockObject
     */
    private $redis;

    /**
     * Set up
     */
    public function setUp()
    {
        if (!class_exists('Redis')) {
            $this->markTestSkipped('Not install PHP Redis extension.');
        }

        $this->redis = $this->getMock(
            'Redis',
            array('lPop', 'rPush')
        );
    }

    /**
     * Test send message without errors
     */
    public function testAddMessage()
    {
        $message = new Message();

        $this->redis->expects($this->once())->method('rPush')
            ->with('foo.bar', serialize($message))->will($this->returnValue(true));

        $adapter = new RedisAdapter();
        $adapter
            ->setListKey('foo.bar')
            ->setRedis($this->redis);

        $adapter->addMessage($message);
    }

    /**
     * Test add message with error: List key not found
     *
     * @expectedException \RuntimeException
     */
    public function testAddMessageListKeyNotFound()
    {
        $adapter = new RedisAdapter();
        $adapter->setRedis($this->redis);
        $adapter->addMessage(new Message());
    }

    /**
     * Test add message with error: Redis instance not found
     *
     * @expectedException \RuntimeException
     */
    public function testAddMessageRedisNotFound()
    {
        $adapter = new RedisAdapter();
        $adapter->setListKey('foo.bar');
        $adapter->addMessage(new Message());
    }

    /**
     * Get message without error
     */
    public function testGetMessageWithExistsMessage()
    {
        $message = new Message();

        $this->redis->expects($this->once())->method('lPop')
            ->with('foo.bar')->will($this->returnValue(serialize($message)));

        $adapter = new RedisAdapter();
        $adapter
            ->setRedis($this->redis)
            ->setListKey('foo.bar');

        $returnMessage = $adapter->getMessage();
        $this->assertInstanceOf('Apple\ApnPush\Notification\Message', $returnMessage);
    }

    /**
     * Get message with message not found
     */
    public function testGetMessageWithNoExistsMessage()
    {
        $this->redis->expects($this->once())->method('lPop')
            ->with('foo.bar')->will($this->returnValue(false));

        $adapter = new RedisAdapter();
        $adapter
            ->setRedis($this->redis)
            ->setListKey('foo.bar');

        $this->assertNull($adapter->getMessage());
    }

    /**
     * Test get message with error: Redis instance not found
     *
     * @expectedException \RuntimeException
     */
    public function testGetMessageRedisNotFound()
    {
        $adapter = new RedisAdapter();
        $adapter->setListKey('foo.bar');
        $adapter->getMessage();
    }

    /**
     * Get get message with error: List key not found
     *
     * @expectedException \RuntimeException
     */
    public function testGetMessageListKeyNotFound()
    {
        $adapter = new RedisAdapter();
        $adapter->setRedis($this->redis);
        $adapter->getMessage();
    }
}