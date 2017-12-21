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
use Apple\ApnPush\Jwt\JwtInterface;
use Apple\ApnPush\Protocol\Http\Authenticator\CertificateAuthenticator;
use Apple\ApnPush\Protocol\Http\Authenticator\JwtAuthenticator;
use Apple\ApnPush\Protocol\Http\Request;
use PHPUnit\Framework\TestCase;

class JwtAuthenticatorTest extends TestCase
{
    /**
     * @var JwtInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jwt;

    /**
     * @var JwtAuthenticator |\PHPUnit_Framework_MockObject_MockObject
     */
    private $authenticator;

    /**
     * @var string
    */
    private $tmpFileName;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->jwt = $this->createMock(JwtInterface::class);
        $this->authenticator = new JwtAuthenticator($this->jwt);
        $this->tmpFileName = tempnam(sys_get_temp_dir(), 'apn_push_test_jwt');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if (file_exists($this->tmpFileName)) {
            unlink($this->tmpFileName);
        }
    }

    /**
     * @test
     */
    public function shouldChangeJwsLifetime()
    {
        $lifeTime = 1000;
        $this->authenticator->setJwsLifetime($lifeTime);
        self::assertEquals($lifeTime, $this->authenticator->getJwsLifetime());
    }
}
