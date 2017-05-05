<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Jwt;

use Apple\ApnPush\Jwt\Jwt;
use PHPUnit\Framework\TestCase;

class JwtTest extends TestCase
{
    /**
     * @var string
     */
    private $tmpFileName;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
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
    public function shouldSuccessCreate()
    {
        file_put_contents($this->tmpFileName, 'File for test Json Web Token (Apple/ApnPush)');

        $token = new Jwt('team id', 'key', $this->tmpFileName);

        self::assertEquals($this->tmpFileName, $token->getPath());
        self::assertEquals('team id', $token->getTeamId());
        self::assertEquals('key', $token->getKey());
    }

    /**
     * @test
     *
     * @expectedException \Apple\ApnPush\Exception\CertificateFileNotFoundException
     */
    public function shouldFailCreateIfFileNotFound()
    {
        new Jwt('team id', 'key', $this->tmpFileName.'.failed');
    }
}
