<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

/**
 * Control errors
 */
class SendExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Notification
     */
    protected $notification;

    /**
     * Create new mock connection for testing exceptions
     *
     * @param string $responseData      Binary response package
     * @return \Apple\ApnPush\Notification\Notification
     */
    protected function createNotification($responseData)
    {
        $connectionMock = $this->getMock(
            'Apple\ApnPush\Notification\Connection',
            array('is', 'isReadyRead', 'write', 'read', 'connect'),
            array(),
            '',
            false
        );

        $connectionMock->expects($this->any())
            ->method('isReadyRead')
            ->will($this->returnValue(true));

        $connectionMock->expects($this->any())
            ->method('read')
            ->with(6)
            ->will($this->returnValue($responseData));

        $notification = new Notification;
        $payload = new PayloadFactory;

        $notification->setConnection($connectionMock);
        $notification->setPayloadFactory($payload);

        return $notification;
    }

    /**
     * @dataProvider notificationProvider
     * @expectedException \Apple\ApnPush\Notification\SendException
     */
    public function testNotificationException($response, $statusCode)
    {
        $notification = $this->createNotification($response);

        $message = new Message;
        $message->setDeviceToken(str_repeat('af', 32));
        $message->setBody('foo');

        try {
            $notification->send($message);
        } catch (SendException $exception) {
            $this->assertEquals($statusCode, $exception->getStatusCode());
            throw $exception;
        }
    }

    /**
     * Provider
     */
    public function notificationProvider()
    {
        return array(
            array(pack('CCN', 1, 1, 0), SendException::ERROR_PROCESSING),
            array(pack('CCN', 1, 2, 0), SendException::ERROR_MISSING_DEVICE_TOKEN),
            array(pack('CCN', 1, 3, 0), SendException::ERROR_MISSING_TOPIC),
            array(pack('CCN', 1, 4, 0), SendException::ERROR_MISSING_PAYLOAD),
            array(pack('CCN', 1, 5, 0), SendException::ERROR_INVALID_TOKEN_SIZE),
            array(pack('CCN', 1, 6, 0), SendException::ERROR_INVALID_TOPIC_SIZE),
            array(pack('CCN', 1, 7, 0), SendException::ERROR_INVALID_PAYLOAD_SIZE),
            array(pack('CCN', 1, 8, 0), SendException::ERROR_INVALID_TOKEN),
            array(pack('CCN', 1, 255, 0), SendException::ERROR_UNKNOWN),
            array('', SendException::ERROR_UNPACK_RESPONSE)
        );
    }
}
