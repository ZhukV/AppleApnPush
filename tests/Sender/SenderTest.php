<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Sender;

use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Model\Receiver;
use Apple\ApnPush\Protocol\ProtocolInterface;
use Apple\ApnPush\Sender\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    /**
     * @var ProtocolInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $protocol;

    /**
     * @var Sender
     */
    private $sender;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->protocol = $this->createMock(ProtocolInterface::class);
        $this->sender = new Sender($this->protocol);
    }

    /**
     * @test
     */
    public function shouldSuccessSend()
    {
        $token = new DeviceToken(str_repeat('af', 32));
        $receiver = new Receiver($token, 'com.domain');
        $payload = new Payload(new Aps(new Alert()));
        $notification = new Notification($payload);

        $this->protocol->expects(self::once())
            ->method('send')
            ->with($receiver, $notification, false);

        $this->sender->send($receiver, $notification, false);
    }
}
