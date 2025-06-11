<?php

declare(strict_types = 1);

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Protocol;

use Apple\ApnPush\Encoder\PayloadEncoderInterface;
use Apple\ApnPush\Exception\SendNotification\SendNotificationException;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Receiver;
use Apple\ApnPush\Protocol\Http\Authenticator\AuthenticatorInterface;
use Apple\ApnPush\Protocol\Http\ExceptionFactory\ExceptionFactoryInterface;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Sender\Exception\HttpSenderException;
use Apple\ApnPush\Protocol\Http\Sender\HttpSenderInterface;
use Apple\ApnPush\Protocol\Http\UriFactory\UriFactoryInterface;
use Apple\ApnPush\Protocol\Http\Visitor\HttpProtocolVisitorInterface;

class HttpProtocol implements ProtocolInterface
{
    private AuthenticatorInterface $authenticator;
    private HttpSenderInterface $httpSender;
    private PayloadEncoderInterface $payloadEncoder;
    private UriFactoryInterface$uriFactory;
    private HttpProtocolVisitorInterface $visitor;
    private ExceptionFactoryInterface $exceptionFactory;

    public function __construct(AuthenticatorInterface $authenticator, HttpSenderInterface $httpSender, PayloadEncoderInterface $payloadEncoder, UriFactoryInterface $uriFactory, HttpProtocolVisitorInterface $visitor, ExceptionFactoryInterface $exceptionFactory)
    {
        $this->authenticator = $authenticator;
        $this->httpSender = $httpSender;
        $this->payloadEncoder = $payloadEncoder;
        $this->uriFactory = $uriFactory;
        $this->visitor = $visitor;
        $this->exceptionFactory = $exceptionFactory;
    }

    public function send(Receiver $receiver, Notification $notification, bool $sandbox): void
    {
        try {
            $this->doSend($receiver, $notification, $sandbox);
        } catch (HttpSenderException $e) {
            $this->httpSender->close();

            throw $e;
        }
    }

    public function closeConnection(): void
    {
        $this->httpSender->close();
    }

    private function doSend(Receiver $receiver, Notification $notification, bool $sandbox): void
    {
        $payloadEncoded = $this->payloadEncoder->encode($notification->getPayload());
        $uri = $this->uriFactory->create($receiver->getToken(), $sandbox);

        $request = new Request($uri, $payloadEncoded);

        $headers = [
            'content-type' => 'application/json',
            'accept'       => 'application/json',
            'apns-topic'   => $receiver->getTopic(),
        ];

        $request = $request->withHeaders($headers);
        $request = $this->authenticator->authenticate($request);

        $request = $this->visitor->visit($notification, $request);

        $response = $this->httpSender->send($request);

        if ($response->getStatusCode() !== 200) {
            throw $this->exceptionFactory->create($response);
        }
    }
}
