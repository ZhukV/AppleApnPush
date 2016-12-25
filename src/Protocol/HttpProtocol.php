<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Protocol;

use Apple\ApnPush\Encoder\MessageEncoderInterface;
use Apple\ApnPush\Exception\SendMessage\SendMessageException;
use Apple\ApnPush\Model\Message;
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
     * @var MessageEncoderInterface
     */
    private $messageEncoder;

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
     * @param MessageEncoderInterface      $messageEncoder
     * @param UriFactoryInterface          $uriFactory
     * @param HttpProtocolVisitorInterface $visitor
     * @param ExceptionFactoryInterface    $exceptionFactory
     */
    public function __construct(
        AuthenticatorInterface $authenticator,
        HttpSenderInterface $httpSender,
        MessageEncoderInterface $messageEncoder,
        UriFactoryInterface $uriFactory,
        HttpProtocolVisitorInterface $visitor,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        $this->authenticator = $authenticator;
        $this->httpSender = $httpSender;
        $this->messageEncoder = $messageEncoder;
        $this->uriFactory = $uriFactory;
        $this->visitor = $visitor;
        $this->exceptionFactory = $exceptionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Receiver $receiver, Message $message, bool $sandbox)
    {
        try {
            $this->doSend($receiver, $message, $sandbox);
        } catch (SendMessageException $e) {
            $this->httpSender->close();

            throw $e;
        }
    }

    /**
     * Inner send process
     *
     * @param Receiver $receiver
     * @param Message  $message
     * @param bool     $sandbox
     *
     * @throws SendMessageException
     */
    private function doSend(Receiver $receiver, Message $message, bool $sandbox)
    {
        $content = $this->messageEncoder->encode($message);
        $uri = $this->uriFactory->create($receiver->getToken(), $sandbox);

        $request = new Request($uri, $content);

        $headers = [
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'apns-topic' => $receiver->getTopic(),
        ];

        $request = $request->withHeaders($headers);
        $request = $this->authenticator->authenticate($request);

        $request = $this->visitor->visit($message, $request);

        $response = $this->httpSender->send($request);

        if ($response->getStatusCode() !== 200) {
            throw $this->exceptionFactory->create($response);
        }
    }
}
