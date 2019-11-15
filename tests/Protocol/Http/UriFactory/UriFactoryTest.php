<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Protocol\Http\UriFactory;

use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Protocol\Http\UriFactory\UriFactory;
use PHPUnit\Framework\TestCase;

class UriFactoryTest extends TestCase
{
    /**
     * @var UriFactory
     */
    private $uriFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uriFactory = new UriFactory();
    }

    /**
     * @test
     */
    public function shouldSuccessCreateForProductionMode()
    {
        $token = new DeviceToken(str_repeat('af', 32));
        $uri = $this->uriFactory->create($token, false);

        self::assertEquals(
            'https://api.push.apple.com/3/device/afafafafafafafafafafafafafafafafafafafafafafafafafafafafafafafaf',
            $uri
        );
    }

    /**
     * @test
     */
    public function shouldSuccessCreateForDevelopmentMode()
    {
        $token = new DeviceToken(str_repeat('aa', 32));
        $uri = $this->uriFactory->create($token, true);

        self::assertEquals(
            'https://api.development.push.apple.com/3/device/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            $uri
        );
    }
}
