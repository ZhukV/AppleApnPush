<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Protocol\Http\Authenticator;

use Apple\ApnPush\Jwt\JwtInterface;
use Apple\ApnPush\Jwt\SignatureGenerator\SignatureGeneratorInterface;
use Apple\ApnPush\Protocol\Http\Authenticator\JwtAuthenticator;
use Apple\ApnPush\Protocol\Http\Request;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class JwtAuthenticatorTest extends TestCase
{
    /**
     * @var SignatureGeneratorInterface|MockObject
     */
    private $signatureGenerator;

    /**
     * @var JwtInterface|MockObject
     */
    private $jwt;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->signatureGenerator = $this->createMock(SignatureGeneratorInterface::class);
        $this->jwt = $this->createMock(JwtInterface::class);
    }

    /**
     * @test
     */
    public function shouldSuccessAuthenticate(): void
    {
        $this->signatureGenerator->expects(self::once())
            ->method('generate')
            ->with($this->jwt)
            ->willReturn('some-authenticated-token');

        $authenticator = new JwtAuthenticator($this->jwt, null, $this->signatureGenerator);
        $request = new Request('https://some-foo-bar.com', 'some');

        $resultRequest = $authenticator->authenticate($request);

        self::assertNotEquals(spl_object_hash($request), spl_object_hash($resultRequest));

        self::assertEquals([
            'authorization' => 'bearer some-authenticated-token',
        ], $resultRequest->getHeaders());
    }

    /**
     * @test
     */
    public function shouldDoNotGenerateNewTokenWithLifetime(): void
    {
        $this->signatureGenerator->expects(self::once())
            ->method('generate')
            ->with($this->jwt)
            ->willReturn('some-authenticated-token');

        $authenticator = new JwtAuthenticator($this->jwt, new \DateInterval('PT1M'), $this->signatureGenerator);
        $request = new Request('https://some-foo-bar.com', 'some');

        $resultRequest1 = $authenticator->authenticate($request);
        $resultRequest2 = $authenticator->authenticate($resultRequest1);
        $resultRequest3 = $authenticator->authenticate($resultRequest2);

        self::assertNotEquals(spl_object_hash($request), spl_object_hash($resultRequest3));

        self::assertEquals([
            'authorization' => 'bearer some-authenticated-token',
        ], $resultRequest3->getHeaders());
    }

    /**
     * @test
     */
    public function shouldRegenerateWithoutLifetime(): void
    {
        $this->signatureGenerator->expects(self::exactly(3))
            ->method('generate')
            ->with($this->jwt)
            ->willReturn(
                'some-authenticated-token-1',
                'some-authenticated-token-2',
                'some-authenticated-token-3'
            );

        $authenticator = new JwtAuthenticator($this->jwt, null, $this->signatureGenerator);
        $request = new Request('https://some-foo-bar.com', 'some');

        $resultRequest1 = $authenticator->authenticate($request);
        $resultRequest2 = $authenticator->authenticate($resultRequest1);
        $resultRequest3 = $authenticator->authenticate($resultRequest2);

        self::assertNotEquals(spl_object_hash($request), spl_object_hash($resultRequest3));

        self::assertEquals([
            'authorization' => 'bearer some-authenticated-token-3',
        ], $resultRequest3->getHeaders());
    }
}
