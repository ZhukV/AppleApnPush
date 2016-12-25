<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Protocol;

use Apple\ApnPush\Encoder\MessageEncoderInterface;
use Apple\ApnPush\Exception\SendMessage\SendMessageException;
use Apple\ApnPush\Model\ApsData;
use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Message;
use Apple\ApnPush\Model\Receiver;
use Apple\ApnPush\Protocol\Http\Authenticator\AuthenticatorInterface;
use Apple\ApnPush\Protocol\Http\ExceptionFactory\ExceptionFactoryInterface;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Response;
use Apple\ApnPush\Protocol\Http\Sender\HttpSenderInterface;
use Apple\ApnPush\Protocol\Http\UriFactory\UriFactoryInterface;
use Apple\ApnPush\Protocol\Http\Visitor\HttpProtocolVisitorInterface;
use Apple\ApnPush\Protocol\HttpProtocol;
use PHPUnit\Framework\TestCase;

class HttpProtocolTest extends TestCase
{
    /**
     * @var AuthenticatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $authenticator;

    /**
     * @var HttpSenderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpSender;

    /**
     * @var MessageEncoderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $messageEncoder;

    /**
     * @var UriFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $uriFactory;

    /**
     * @var HttpProtocolVisitorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $visitor;

    /**
     * @var ExceptionFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $exceptionFactory;

    /**
     * @var HttpProtocol
     */
    private $protocol;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->authenticator = self::createMock(AuthenticatorInterface::class);
        $this->httpSender = self::createMock(HttpSenderInterface::class);
        $this->messageEncoder = self::createMock(MessageEncoderInterface::class);
        $this->uriFactory = self::createMock(UriFactoryInterface::class);
        $this->visitor = self::createMock(HttpProtocolVisitorInterface::class);
        $this->exceptionFactory = self::createMock(ExceptionFactoryInterface::class);

        $this->protocol = new HttpProtocol(
            $this->authenticator,
            $this->httpSender,
            $this->messageEncoder,
            $this->uriFactory,
            $this->visitor,
            $this->exceptionFactory
        );
    }

    /**
     * @test
     */
    public function shouldSuccessSend()
    {
        $deviceToken = new DeviceToken(str_repeat('af', 32));
        $receiver = new Receiver($deviceToken, 'com.test');
        $message = new Message(new ApsData());

        $this->messageEncoder->expects(self::once())
            ->method('encode')
            ->with($message)
            ->willReturn('{"aps":{}}');

        $this->uriFactory->expects(self::once())
            ->method('create')
            ->with($deviceToken, false)
            ->willReturn('https://some.com/'.$deviceToken);

        $this->authenticator->expects(self::once())
            ->method('authenticate')
            ->with(self::isInstanceOf(Request::class))
            ->willReturnCallback(function (Request $innerRequest) {
                return $innerRequest;
            });

        $this->visitor->expects(self::once())
            ->method('visit')
            ->with($message, self::isInstanceOf(Request::class));

        $this->httpSender->expects(self::once())
            ->method('send')
            ->with(self::isInstanceOf(Request::class))
            ->willReturn(new Response(200, '{}'));

        $this->protocol->send($receiver, $message, false);
    }

    /**
     * @test
     *
     * @expectedException \Apple\ApnPush\Exception\SendMessage\SendMessageException
     */
    public function shouldFailSend()
    {
        $deviceToken = new DeviceToken(str_repeat('af', 32));
        $receiver = new Receiver($deviceToken, 'com.test');
        $message = new Message(new ApsData());

        $this->messageEncoder->expects(self::once())
            ->method('encode')
            ->with($message)
            ->willReturn('{"aps":{}}');

        $this->uriFactory->expects(self::once())
            ->method('create')
            ->with($deviceToken, false)
            ->willReturn('https://some.com/'.$deviceToken);

        $this->authenticator->expects(self::once())
            ->method('authenticate')
            ->with(self::isInstanceOf(Request::class))
            ->willReturnCallback(function (Request $innerRequest) {
                return $innerRequest;
            });

        $this->visitor->expects(self::once())
            ->method('visit')
            ->with($message, self::isInstanceOf(Request::class));

        $this->httpSender->expects(self::once())
            ->method('send')
            ->with(self::isInstanceOf(Request::class))
            ->willReturn(new Response(404, '{}'));

        $this->httpSender->expects(self::once())
            ->method('close');

        $this->exceptionFactory->expects(self::once())
            ->method('create')
            ->with(new Response(404, '{}'))
            ->willReturn(self::createMock(SendMessageException::class));

        $this->protocol->send($receiver, $message, false);
    }
}
