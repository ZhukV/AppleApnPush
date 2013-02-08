<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

use Apple\ApnPush\Connection\Connection;
use Apple\ApnPush\PayloadFactory\PayloadFactory;
use Apple\ApnPush\Messages\DefaultMessage;
use Apple\ApnPush\Messages\MessageInterface;

/**
 * Notification test
 */
class NotificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Notification
     */
    protected $notification;

    /**
     * Create notification
     */
    public function createNotification()
    {
        if ($this->notification) {
            return $this->notification;
        }

        $notification = new Notification;
        $connection = new Connection(__FILE__, 'pass', false);
        $payload = new PayloadFactory;
        $notification->setPayloadFactory($payload);
        $notification->setConnection($connection);

        $socketMock = $this->getMock(
            'RequestStream\\Stream\\Socket\\SocketClient',
            array('create', 'write', 'read', 'selectRead', 'setBlocking', 'is')
        );

        // Replace create
        $socketMock->expects($this->once())->method('create')->will($this->returnCallback(function() use ($socketMock) {
            $socketMock->__Create__ = true;
        }));

        // Replace is method
        $socketMock->expects($this->any())->method('is')->will($this->returnCallback(function($autload = false) use ($socketMock) {
            return !empty($socketMock->__Create__);
        }));

        // Replace write
        $socketMock->expects($this->any())->method('write')->will($this->returnCallback(function($text, $size = null) {
            return mb_strlen($text);
        }));

        // Read
        $socketMock->expects($this->any())->method('read')->will($this->returnCallback(function($length) {
            return str_repeat('a', $length);
        }));

        // Select blocking
        $socketMock->expects($this->any())->method('selectRead')->will($this->returnValue(false));

        $refSocket = new \ReflectionProperty($connection, 'socketConnection');
        $refSocket->setAccessible(TRUE);
        $refSocket->setValue($connection, $socketMock);

        $this->notification = $notification;
        return $this->notification;
    }

    /**
     * @dataProvider notificationProvider
     */
    public function testNotification(MessageInterface $message)
    {
        $notification = $this->createNotification();
        $this->assertTrue($notification->sendMessage($message));
    }

    /**
     * Notification test provider
     */
    static public function notificationProvider()
    {
        return array(
            array(self::createMessage('test1')),
            array(self::createMessage('test2', 1)),
            array(self::createMessage('test3', 2, str_repeat('12', 32)))
        );
    }

    /**
     * Create message
     */
    static public function createMessage($body, $identifier = null, $deviceToken = null)
    {
        $message = new DefaultMessage;

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
