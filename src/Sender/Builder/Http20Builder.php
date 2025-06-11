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

namespace Apple\ApnPush\Sender\Builder;

use Apple\ApnPush\Encoder\PayloadEncoder;
use Apple\ApnPush\Encoder\PayloadEncoderInterface;
use Apple\ApnPush\Protocol\Http\Authenticator\AuthenticatorInterface;
use Apple\ApnPush\Protocol\Http\ExceptionFactory\ExceptionFactory;
use Apple\ApnPush\Protocol\Http\ExceptionFactory\ExceptionFactoryInterface;
use Apple\ApnPush\Protocol\Http\Sender\CurlHttpSender;
use Apple\ApnPush\Protocol\Http\Sender\HttpSenderInterface;
use Apple\ApnPush\Protocol\Http\UriFactory\UriFactory;
use Apple\ApnPush\Protocol\Http\UriFactory\UriFactoryInterface;
use Apple\ApnPush\Protocol\Http\Visitor\AddApnIdHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\AddCollapseIdHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\AddExpirationHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\AddPriorityHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\AddPushTypeHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\HttpProtocolChainVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\HttpProtocolVisitorInterface;
use Apple\ApnPush\Protocol\HttpProtocol;
use Apple\ApnPush\Protocol\ProtocolInterface;
use Apple\ApnPush\Sender\Sender;
use Apple\ApnPush\Sender\SenderInterface;

/**
 * Builder for create sender with HTTP/2 protocol
 */
class Http20Builder implements BuilderInterface
{
    /**
     * @var \SplPriorityQueue<int, HttpProtocolVisitorInterface>
     */
    private \SplPriorityQueue $visitors;

    private UriFactoryInterface $uriFactory;
    private PayloadEncoderInterface $payloadEncoder;
    private AuthenticatorInterface $authenticator;
    private HttpSenderInterface $httpSender;
    private ExceptionFactoryInterface $exceptionFactory;

    public function __construct(AuthenticatorInterface $authenticator)
    {
        $this->authenticator = $authenticator;
        $this->visitors = new \SplPriorityQueue();
        $this->uriFactory = new UriFactory();
        $this->payloadEncoder = new PayloadEncoder();
        $this->httpSender = new CurlHttpSender();
        $this->exceptionFactory = new ExceptionFactory();

        $this->addVisitor(new AddExpirationHeaderVisitor());
        $this->addVisitor(new AddPriorityHeaderVisitor());
        $this->addVisitor(new AddApnIdHeaderVisitor());
        $this->addVisitor(new AddCollapseIdHeaderVisitor());
        $this->addVisitor(new AddPushTypeHeaderVisitor());
    }

    public function setAuthenticator(AuthenticatorInterface $authenticator): self
    {
        $this->authenticator = $authenticator;

        return $this;
    }

    public function addVisitor(HttpProtocolVisitorInterface $visitor, int $priority = 0): self
    {
        $this->visitors->insert($visitor, $priority);

        return $this;
    }

    public function setUriFactory(UriFactoryInterface $uriFactory): self
    {
        $this->uriFactory = $uriFactory;

        return $this;
    }

    public function setPayloadEncoder(PayloadEncoderInterface $payloadEncoder): self
    {
        $this->payloadEncoder = $payloadEncoder;

        return $this;
    }

    public function setHttpSender(HttpSenderInterface $httpSender): self
    {
        $this->httpSender = $httpSender;

        return $this;
    }

    public function setExceptionFactory(ExceptionFactoryInterface $exceptionFactory): self
    {
        $this->exceptionFactory = $exceptionFactory;

        return $this;
    }

    public function buildProtocol(): ProtocolInterface
    {
        $chainVisitor = $this->createChainVisitor();

        return new HttpProtocol(
            $this->authenticator,
            $this->httpSender,
            $this->payloadEncoder,
            $this->uriFactory,
            $chainVisitor,
            $this->exceptionFactory
        );
    }

    public function build(): SenderInterface
    {
        $protocol = $this->buildProtocol();

        return new Sender($protocol);
    }

    private function createChainVisitor(): HttpProtocolChainVisitor
    {
        $chainVisitors = new HttpProtocolChainVisitor();
        $visitors = clone $this->visitors;
        $priority = 0;

        foreach ($visitors as $visitor) {
            $chainVisitors->add($visitor, ++$priority);
        }

        return $chainVisitors;
    }
}
