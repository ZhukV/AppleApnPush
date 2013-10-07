<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Notification test
 */
class NotificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \RequestStream\Stream\Socket\SocketClient|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $socketConnection;

    /**
     * @var \Apple\ApnPush\Notification\Connection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $connection;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->socketConnection = $this->getMock(
            'RequestStream\Stream\Socket\SocketClient',
            array('create', 'write', 'read', 'selectRead', 'setBlocking', 'is', 'close')
        );

        $this->connection = $this->getMock(
            'Apple\ApnPush\Notification\Connection',
            array('is', 'write', 'isReadyRead', 'create', 'close', 'read')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        unset ($this->socketConnection, $this->connection);
    }

    /**
     * @dataProvider notificationProvider
     */
    public function testNotification(MessageInterface $message)
    {
        // Create notification
        $notification = new Notification;
        $connection = new Connection(__FILE__, 'pass', false);
        $payload = new PayloadFactory;
        $notification->setPayloadFactory($payload);
        $notification->setConnection($connection);

        // Get socket connection mock
        /** @var \PHPUnit_Framework_MockObject_MockObject $socketMock */
        $socketMock = $this->socketConnection;

        $socketMock->expects($this->any())->method('is')
            ->will($this->returnValue(false));

        $socketMock->expects($this->once())->method('create');

        $socketMock->expects($this->once())->method('setBlocking')
            ->with($this->equalTo(0));

        $socketMock->expects($this->once())->method('write')
            ->will($this->returnCallback(function($text, $size = null) {
                return mb_strlen($text);
            }));

        $socketMock->expects($this->once())->method('selectRead')
            ->with($this->equalTo(1), $this->equalTo(0))
            ->will($this->returnValue(false));

        $socketMock->expects($this->never())
            ->method('read');

        // Set socket mock to connection
        $ref = new \ReflectionProperty($connection, 'socketConnection');
        $ref->setAccessible(true);
        $ref->setValue($connection, $socketMock);

        // Testing send message
        $this->assertTrue($notification->send($message));
    }

    /**
     * Notification test provider
     */
    public static function notificationProvider()
    {
        return array(
            array(self::createMessage('test1')),
            array(self::createMessage('test2', 1)),
            array(self::createMessage('test3', 2, str_repeat('12', 32)))
        );
    }

    /**
     * Test reopen connection if send push aborted
     */
    public function testReopenConnection()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $socketMock */
        $socketMock = $this->socketConnection;

        $socketMock->expects($this->any())->method('create')->will($this->returnCallback(function() use ($socketMock) {
            $socketMock->__Create__ = true;
        }));

        $socketMock->expects($this->any())->method('is')->will($this->returnCallback(function() use ($socketMock) {
            return !empty($socketMock->__Create__);
        }));

        $socketMock->expects($this->any())->method('close')->will($this->returnCallback(function() use ($socketMock) {
            unset ($socketMock->__Create__);
        }));

        $socketMock->expects($this->any())->method('write')->will($this->returnCallback(function($content) use ($socketMock) {
            return mb_strlen($content);
        }));

        $socketMock->expects($this->any())->method('selectRead')
            ->with($this->equalTo(1), $this->equalTo(0))
            ->will($this->returnCallback(function() use ($socketMock) {
                if (isset($socketMock->__NotError__)) {
                    return false;
                }

                return true;
            }));

        $socketMock->expects($this->any())->method('read')->will($this->returnCallback(function($size) use ($socketMock) {
            if (isset($socketMock->__NotError__)) {
                return null;
            }

            return pack('CCN', 1, 8, 0);
        }));

        // Create notification manager
        $notification = new Notification;
        $connection = new Connection(__FILE__, 'pass', false);
        $payload = new PayloadFactory;
        $notification->setPayloadFactory($payload);
        $notification->setConnection($connection);

        // Replace socket connection
        $refSocket = new \ReflectionProperty($connection, 'socketConnection');
        $refSocket->setAccessible(true);
        $refSocket->setValue($connection, $socketMock);

        try {
            // Force error
            $notification->send(self::createMessage('test1'));
        } catch (SendException $e) {
            // Nothing
        }

        // Test auto close connection
        $originConnection = $notification->getConnection();
        $this->assertFalse($originConnection->is());

        // Disallow error
        $socketMock->__NotError__ = true;

        // Test reopen connection
        $notification->send(self::createMessage('test2'));
        $this->assertTrue($originConnection->is());
    }

    /**
     * Test call error event
     *
     * @expectedException \Apple\ApnPush\Notification\SendException
     */
    public function testSendMessageEventError()
    {
        if (!class_exists('Symfony\Component\EventDispatcher\EventDispatcher')) {
            $this->markTestSkipped('Not found package "symfony/event-dispatcher".');
        }

        $message = self::createMessage('Foo');

        $this->connection->expects($this->any())->method('is')
            ->will($this->returnValue(true));

        $this->connection->expects($this->once())->method('isReadyRead')
            ->will($this->returnValue(true));

        $this->connection->expects($this->once())->method('write')
            ->will($this->returnCallback(function ($a) { return mb_strlen($a); }));

        $eventDispatcher = $this->getMock(
            'Symfony\Component\EventDispatcher\EventDispatcher',
            array('dispatch')
        );

        $eventDispatcher->expects($this->once())->method('dispatch')
            ->with(
                NotificationEvents::SEND_MESSAGE_ERROR,
                $this->isInstanceOf('Apple\ApnPush\Notification\Events\SendMessageErrorEvent'
            ));

        $notification = new Notification();
        $notification->setPayloadFactory(new PayloadFactory());
        $notification->setConnection($this->connection);
        $notification->setEventDispatcher($eventDispatcher);

        $notification->send($message);
    }

    /**
     * Test call complete event
     */
    public function testSendMessageEventComplete()
    {
        if (!class_exists('Symfony\Component\EventDispatcher\EventDispatcher')) {
            $this->markTestSkipped('Not found package "symfony/event-dispatcher".');
        }

        $message = self::createMessage('Foo');

        $this->connection->expects($this->any())->method('is')
            ->will($this->returnValue(true));

        $this->connection->expects($this->once())->method('isReadyRead')
            ->will($this->returnValue(false));

        $this->connection->expects($this->once())->method('write')
            ->will($this->returnCallback(function ($a) { return mb_strlen($a); }));

        $eventDispatcher = $this->getMock(
            'Symfony\Component\EventDispatcher\EventDispatcher',
            array('dispatch')
        );

        $eventDispatcher->expects($this->once())->method('dispatch')
            ->with(
                NotificationEvents::SEND_MESSAGE_COMPLETE,
                $this->isInstanceOf('Apple\ApnPush\Notification\Events\SendMessageCompleteEvent'
            ));

        $notification = new Notification();
        $notification->setPayloadFactory(new PayloadFactory());
        $notification->setConnection($this->connection);
        $notification->setEventDispatcher($eventDispatcher);

        $notification->send($message);
    }

    /**
     * Create message
     */
    public static function createMessage($body, $identifier = null, $deviceToken = null)
    {
        $message = new Message;

        $message->setBody($body);

        if ($identifier !== null) {
            $message->setIdentifier($identifier);
        }

        if ($deviceToken !== null) {
            $message->setDeviceToken($deviceToken);
        } else {
            $message->setDeviceToken(str_repeat('af', 32));
        }

        return $message;
    }
}
