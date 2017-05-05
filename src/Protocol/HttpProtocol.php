<?php

declare(strict_types=1);

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
use Apple\ApnPush\Protocol\Http\Sender\HttpSenderInterface;
use Apple\ApnPush\Protocol\Http\UriFactory\UriFactoryInterface;
use Apple\ApnPush\Protocol\Http\Visitor\HttpProtocolVisitorInterface;

/**
 * Implement HTTP protocol for send push notification
 */
class HttpProtocol implements ProtocolInterface
{
    /**
     * @var AuthenticatorInterface
     */
    private $authenticator;

    /**
     * @var HttpSenderInterface
     */
    private $httpSender;

    /**
     * @var PayloadEncoderInterface
     */
    private $payloadEncoder;

    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var HttpProtocolVisitorInterface
     */
    private $visitor;

    /**
     * @var ExceptionFactoryInterface
     */
    private $exceptionFactory;

    /**
     * Constructor.
     *
     * @param AuthenticatorInterface       $authenticator
     * @param HttpSenderInterface          $httpSender
     * @param PayloadEncoderInterface      $payloadEncoder
     * @param UriFactoryInterface          $uriFactory
     * @param HttpProtocolVisitorInterface $visitor
     * @param ExceptionFactoryInterface    $exceptionFactory
     */
    public function __construct(
        AuthenticatorInterface $authenticator,
        HttpSenderInterface $httpSender,
        PayloadEncoderInterface $payloadEncoder,
        UriFactoryInterface $uriFactory,
        HttpProtocolVisitorInterface $visitor,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        $this->authenticator = $authenticator;
        $this->httpSender = $httpSender;
        $this->payloadEncoder = $payloadEncoder;
        $this->uriFactory = $uriFactory;
        $this->visitor = $visitor;
        $this->exceptionFactory = $exceptionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Receiver $receiver, Notification $notification, bool $sandbox): void
    {
        try {
            $this->doSend($receiver, $notification, $sandbox);
        } catch (SendNotificationException $e) {
            $this->httpSender->close();

            throw $e;
        }
    }

    /**
     * Inner send process
     *
     * @param Receiver     $receiver
     * @param Notification $notification
     * @param bool         $sandbox
     *
     * @throws SendNotificationException
     */
    private function doSend(Receiver $receiver, Notification $notification, bool $sandbox): void
    {
        $payloadEncoded = $this->payloadEncoder->encode($notification->getPayload());
        $uri = $this->uriFactory->create($receiver->getToken(), $sandbox);

        $request = new Request($uri, $payloadEncoded);

        $headers = [
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'apns-topic' => $receiver->getTopic(),
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
