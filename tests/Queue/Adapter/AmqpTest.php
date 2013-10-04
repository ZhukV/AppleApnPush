<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Queue\Adapter;

use Apple\ApnPush\Notification\Message;
use Apple\ApnPush\Queue\Adapter\AmqpAdapter;

class AmqpAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AMQPQueue|\PHPUnit_Framework_MockObject_MockObject
     */
    private $queue;

    /**
     * @var \AMQPExchange|\PHPUnit_Framework_MockObject_MockObject
     */
    private $exchange;

    /**
     * Set up
     */
    public function setUp()
    {
        if (!class_exists('AMQPConnection')) {
            $this->markTestSkipped('Not install PHP Amqp extension.');
        }

        $this->queue = $this->getMock(
            'AMQPQueue',
            array('get'),
            array(),
            '',
            false
        );

        $this->exchange = $this->getMock(
            'AMQPExchange',
            array('publish'),
            array(),
            '',
            false
        );
    }

    /**
     * Testing send message without errors
     *
     * @dataProvider providerAddMessageParameters
     */
    public function testAddMessage($routingKey, $publishFlag, $publishParameters)
    {
        $message = new Message();
        $message
            ->setDeviceToken(str_repeat('af', 32))
            ->setBody('Foo bar');

        $messageSerialized = serialize($message);

        $this->exchange->expects($this->once())->method('publish')
            ->with($messageSerialized, $routingKey, $publishFlag, $publishParameters)
            ->will($this->returnValue(true));

        $adapter = new AmqpAdapter();
        $adapter
            ->setExchange($this->exchange)
            ->setRoutingKey($routingKey)
            ->setPublishOptions($publishParameters)
            ->setPublishFlag($publishFlag);

        $adapter->addMessage($message);
    }

    public function providerAddMessageParameters()
    {
        return array(
            array('foo', AMQP_NOPARAM, array()),
            array('bar', AMQP_IMMEDIATE, array('expiration' => 100))
        );
    }

    /**
     * Test send message with error: Exchange not found
     *
     * @expectedException \RuntimeException
     */
    public function testAddMessageExchangeNotFound()
    {
        $adapter = new AmqpAdapter();
        $adapter->setQueue($this->queue);
        $adapter->addMessage(new Message());
    }

    /**
     * Test send message with error: Routing key not found
     *
     * @expectedException \RuntimeException
     */
    public function testAddMessageRoutingKeyNotFound()
    {
        $adapter = new AmqpAdapter();
        $adapter->setExchange($this->exchange);
        $adapter->addMessage(new Message());
    }

    /**
     * Test get message without error
     */
    public function testGetMessageWithExistsMessage()
    {
        $message = new Message();
        $message->setBody('Foo Bar');

        $result = $this->getMock('AMQPEnvelope', array('getBody'));
        $result->expects($this->once())->method('getBody')
            ->with()->will($this->returnValue(serialize($message)));

        $this->queue->expects($this->once())->method('get')
            ->with(AMQP_AUTOACK)
            ->will($this->returnValue($result));

        $adapter = new AmqpAdapter();
        $adapter->setQueue($this->queue);

        $message = $adapter->getMessage();
        $this->assertInstanceOf('Apple\ApnPush\Notification\Message', $message);
        $this->assertEquals('Foo Bar', $message->getBody());
    }

    /**
     * Test get message with message not found
     */
    public function testGetMessageWithNoExistsMessage()
    {
        $this->queue->expects($this->once())->method('get')
            ->with(AMQP_AUTOACK)
            ->will($this->returnValue(false));

        $adapter = new AmqpAdapter();
        $adapter->setQueue($this->queue);

        $this->assertNull($adapter->getMessage());
    }

    /**
     * Test get message with error: Queue not found
     *
     * @expectedException \RuntimeException
     */
    public function testGetMessageQueueNotFound()
    {
        $adapter = new AmqpAdapter();
        $adapter->getMessage();
    }
}