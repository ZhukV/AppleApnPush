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

namespace Tests\Apple\ApnPush\Sender\Builder;

use Apple\ApnPush\Encoder\PayloadEncoderInterface;
use Apple\ApnPush\Protocol\Http\Authenticator\AuthenticatorInterface;
use Apple\ApnPush\Protocol\Http\ExceptionFactory\ExceptionFactoryInterface;
use Apple\ApnPush\Protocol\Http\Sender\HttpSenderInterface;
use Apple\ApnPush\Protocol\Http\UriFactory\UriFactoryInterface;
use Apple\ApnPush\Protocol\Http\Visitor\AddApnIdHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\AddCollapseIdHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\AddExpirationHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\AddPriorityHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\AddPushTypeHeaderVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\HttpProtocolChainVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\HttpProtocolVisitorInterface;
use Apple\ApnPush\Protocol\HttpProtocol;
use Apple\ApnPush\Sender\Builder\Http20Builder;
use Apple\ApnPush\Sender\Sender;
use PHPUnit\Framework\TestCase;

class Http20BuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessBuild(): void
    {
        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $builder = new Http20Builder($authenticator);

        $expectedProtocol = $this->prepareBuilderAndCreateProtocol($builder, $authenticator);

        $sender = $builder->build();

        $expectedSender = new Sender($expectedProtocol);

        self::assertInstanceOf(Sender::class, $sender);
        self::assertEquals($expectedSender, $sender);
    }

    /**
     * @test
     */
    public function shouldSuccessBuildProtocol(): void
    {
        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $builder = new Http20Builder($authenticator);

        $expectedProtocol = $this->prepareBuilderAndCreateProtocol($builder, $authenticator);
        $protocol = $builder->buildProtocol();

        self::assertEquals($expectedProtocol, $protocol);
    }

    /**
     * Prepare the builder and create the protocol
     *
     * @param Http20Builder          $builder
     * @param AuthenticatorInterface $authenticator
     *
     * @return HttpProtocol
     */
    private function prepareBuilderAndCreateProtocol(Http20Builder $builder, AuthenticatorInterface $authenticator): HttpProtocol
    {
        $exceptionFactory = $this->createMock(ExceptionFactoryInterface::class);
        $httpSender = $this->createMock(HttpSenderInterface::class);
        $messageEncoder = $this->createMock(PayloadEncoderInterface::class);
        $uriFactory = $this->createMock(UriFactoryInterface::class);
        $visitor = $this->createMock(HttpProtocolVisitorInterface::class);

        $priority = 0;
        $chainVisitor = new HttpProtocolChainVisitor();
        $chainVisitor->add(new AddExpirationHeaderVisitor(), ++$priority);
        $chainVisitor->add(new AddPriorityHeaderVisitor(), ++$priority);
        $chainVisitor->add(new AddApnIdHeaderVisitor(), ++$priority);
        $chainVisitor->add(new AddCollapseIdHeaderVisitor(), ++$priority);
        $chainVisitor->add(new AddPushTypeHeaderVisitor(), ++$priority);
        $chainVisitor->add($visitor, ++$priority);

        $builder
            ->setAuthenticator($authenticator)
            ->setExceptionFactory($exceptionFactory)
            ->setHttpSender($httpSender)
            ->setPayloadEncoder($messageEncoder)
            ->setUriFactory($uriFactory)
            ->addVisitor($visitor);

        return new HttpProtocol(
            $authenticator,
            $httpSender,
            $messageEncoder,
            $uriFactory,
            $chainVisitor,
            $exceptionFactory
        );
    }
}
