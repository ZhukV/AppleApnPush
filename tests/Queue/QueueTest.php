<?php

/*
 * This file is part of the AppleApnPushQueue package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Queue;

use Apple\ApnPush\Notification\Message;
use Apple\ApnPush\Notification\SendException;
use Apple\ApnPush\Queue\Queue;

class QueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Apple\ApnPush\Notification\NotificationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $notification;

    /**
     * @var \Apple\ApnPush\Queue\Adapter\AdapterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $adapter;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->notification = $this->getMock(
            'Apple\ApnPush\Notification\Notification',
            array('send')
        );

        $this->adapter = $this->getMock(
            'Apple\ApnPush\Queue\Adapter\AdapterInterface'
        );
    }

    /**
     * Test send message without errors
     */
    public function testAddMessage()
    {
        $message = new Message();

        $this->adapter->expects($this->once())->method('addMessage')
            ->with($message)->will($this->returnValue(true));

        $queue = new Queue($this->adapter, $this->notification);
        $this->assertTrue($queue->addMessage($message));
    }

    /**
     * Test send message with error: Adapter not found
     *
     * @expectedException \RuntimeException
     */
    public function testAddMessageAdapterNotFound()
    {
        $queue = new Queue();
        $queue->addMessage(new Message());
    }

    /**
     * Test run receiver without errors
     */
    public function testRunReceiver()
    {
        $iterations = 10;

        $this->adapter->expects($this->exactly($iterations))->method('isNextReceive')
            ->with()->will($this->returnCallback(function () use (&$iterations) {
                return --$iterations > 0;
            }));

        $this->adapter->expects($this->exactly($iterations - 1))->method('getMessage')
            ->with()->will($this->returnValue(new Message()));

        $this->notification->expects($this->exactly($iterations - 1))->method('send')
            ->with($this->isInstanceOf('Apple\ApnPush\Notification\Message'))
            ->will($this->returnValue(true));

        $queue = new Queue($this->adapter, $this->notification);
        $queue->runReceiver();
    }

    /**
     * Test run receiver with error: Invalid message
     *
     * @expectedException \Apple\ApnPush\Exception\InvalidMessageException
     */
    public function testRunReceiverInvalidMessage()
    {
        $this->adapter->expects($this->any())->method('isNextReceive')
            ->with()->will($this->returnValue(true));

        $this->adapter->expects($this->once())->method('getMessage')
            ->with()->will($this->returnValue('foo bar'));

        $this->notification->expects($this->never())->method('send');

        $queue = new Queue($this->adapter, $this->notification);
        $queue->runReceiver();
    }

    /**
     * Test run receiver with error: Adapter not found
     *
     * @expectedException \RuntimeException
     */
    public function testRunReceiverAdapterNotFound()
    {
        $queue = new Queue(null, $this->notification);
        $queue->runReceiver();
    }

    /**
     * Test run receiver with error: Notification not found
     *
     * @expectedException \RuntimeException
     */
    public function testRunReceiverNotificationNotFound()
    {
        $queue = new Queue($this->adapter, null);
        $queue->runReceiver();
    }

    /**
     * Test run receiver and control control notification error
     */
    public function testRunReceiverControlNotificationError()
    {
        $iterations = 0;
        $existsControlError = false;

        $this->adapter->expects($this->any())->method('isNextReceive')
            ->with()->will($this->returnCallback(function() use (&$iterations){
                if ($iterations > 0) {
                    return false;
                }
                $iterations++;
                return true;
            }));

        $message = new Message();

        $this->adapter->expects($this->any())->method('getMessage')
            ->with()->will($this->returnValue($message));

        $this->notification->expects($this->any())->method('send')
            ->with($message)->will($this->returnCallback(function(){
                throw new SendException(SendException::ERROR_UNPACK_RESPONSE, null, null);
            }));

        $controlError = function(SendException $error) use (&$existsControlError) {
            $existsControlError = true;
        };

        $queue = new Queue($this->adapter, $this->notification);
        $queue->setNotificationErrorHandler($controlError);
        $queue->runReceiver();

        $this->assertTrue($existsControlError, 'Not call to callback for control notification error.');
    }
}