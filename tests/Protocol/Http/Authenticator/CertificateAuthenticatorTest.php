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

use Apple\ApnPush\Certificate\CertificateInterface;
use Apple\ApnPush\Protocol\Http\Authenticator\CertificateAuthenticator;
use Apple\ApnPush\Protocol\Http\Request;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CertificateAuthenticatorTest extends TestCase
{
    /**
     * @var CertificateInterface|MockObject
     */
    private $certificate;

    /**
     * @var CertificateAuthenticator|MockObject
     */
    private $authenticator;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->certificate = $this->createMock(CertificateInterface::class);
        $this->authenticator = new CertificateAuthenticator($this->certificate);
    }

    /**
     * @test
     */
    public function shouldSuccessAuthenticate()
    {
        $request = new Request('some', '{}', []);

        $this->certificate->expects(self::once())
            ->method('getPath')
            ->willReturn('/path/to/certificate.pem');

        $this->certificate->expects(self::once())
            ->method('getPassPhrase')
            ->willReturn('pass');

        $authenticatedRequest = $this->authenticator->authenticate($request);

        self::assertNotEquals($authenticatedRequest, $request);
        self::assertEquals('/path/to/certificate.pem', $authenticatedRequest->getCertificate());
        self::assertEquals('pass', $authenticatedRequest->getCertificatePassPhrase());
    }
}
