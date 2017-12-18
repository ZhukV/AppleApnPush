<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Encoder;

use Apple\ApnPush\Encoder\PayloadEncoder;
use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\Localized;
use Apple\ApnPush\Model\Payload;
use PHPUnit\Framework\TestCase;

class PayloadEncoderTest extends TestCase
{
    /**
     * @var PayloadEncoder
     */
    private $encoder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->encoder = new PayloadEncoder();
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithBody()
    {
        $alert = new Alert();
        $alert = $alert->withBody('some');

        $payload = new Payload(new Aps($alert));
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":"some"}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithLocalizedBody()
    {
        $alert = new Alert();
        $alert = $alert->withBodyLocalized(new Localized('some', ['key' => 'value']));

        $payload = new Payload(new Aps($alert));
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"loc-key":"some","loc-args":{"key":"value"}}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithTitle()
    {
        $alert = new Alert();
        $alert = $alert->withTitle('some');

        $payload = new Payload(new Aps($alert));
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":"","title":"some"}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithLocalizedTitle()
    {
        $alert = new Alert();
        $alert = $alert->withLocalizedTitle(new Localized('some', ['key' => 'value']));

        $payload = new Payload(new Aps($alert));
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":"","title-loc-key":"some","title-loc-args":{"key":"value"}}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithActionLocalizedKey()
    {
        $alert = new Alert();
        $alert = $alert->withActionLocalized(new Localized('some'));

        $payload = new Payload(new Aps($alert));
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":"","action-loc-key":"some"}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithLaunchImage()
    {
        $alert = new Alert();
        $alert = $alert->withLaunchImage('some');

        $payload = new Payload(new Aps($alert));
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":"","launch-image":"some"}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithCategory()
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withCategory('some');

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"category":"some"}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithBadge()
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withBadge(11);

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"badge":11}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithSound()
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withSound('some');

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"sound":"some"}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithContentAvailable()
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withContentAvailable(true);

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"content-available":1}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithMutableContent()
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withMutableContent(true);

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"mutable-content":1}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithThreadId()
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withThreadId('123');

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"thread-id":"123"}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithCustomData()
    {
        $aps = new Aps(new Alert());
        $payload = new Payload($aps);

        $payload = $payload->withCustomData('some', 'key');
        $payload = $payload->withCustomData('foo', ['bar']);

        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"some":"key","foo":["bar"],"aps":{"alert":{"body":""}}}', $encoded);
    }
}
