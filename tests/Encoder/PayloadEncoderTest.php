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
use Apple\ApnPush\Model\Sound;
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
    protected function setUp(): void
    {
        $this->encoder = new PayloadEncoder();
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithBody(): void
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
    public function shouldSuccessEncodeWithLocalizedBody(): void
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
    public function shouldSuccessEncodeWithTitle(): void
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
    public function shouldSuccessEncodeWithLocalizedTitle(): void
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
    public function shouldSuccessEncodeWithSubtitle(): void
    {
        $alert = new Alert();
        $alert = $alert->withSubtitle('some');

        $payload = new Payload(new Aps($alert));
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":"","subtitle":"some"}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithLocalizedSubtitle(): void
    {
        $alert = new Alert();
        $alert = $alert->withLocalizedSubtitle(new Localized('some', ['key' => 'value']));

        $payload = new Payload(new Aps($alert));
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":"","subtitle-loc-key":"some","subtitle-loc-args":{"key":"value"}}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithActionLocalizedKey(): void
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
    public function shouldSuccessEncodeWithLaunchImage(): void
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
    public function shouldSuccessEncodeWithCategory(): void
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
    public function shouldSuccessEncodeWithBadge(): void
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
    public function shouldSuccessEncodeWithBadgeAsZero(): void
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withBadge(0);

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"badge":0}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithSoundAsString(): void
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
    public function shouldSuccessEncodeWithSoundAsObject(): void
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withSound(new Sound('foo', 0.55, true));

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals(
            '{"aps":{"alert":{"body":""},"sound":{"critical":1,"name":"foo","volume":0.55}}}',
            $encoded
        );
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithContentAvailable(): void
    {
        $aps = new Aps();
        $aps = $aps->withContentAvailable(true);

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"content-available":1}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithMutableContent(): void
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
    public function shouldSuccessEncodeWithThreadId(): void
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
    public function shouldSuccessEncodeWithUrlArgs(): void
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withUrlArgs(['some', '123']);

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"url-args":["some","123"]}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithUrlArgsEmpty(): void
    {
        $aps = new Aps(new Alert());
        $aps = $aps->withUrlArgs([]);

        $payload = new Payload($aps);
        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"url-args":[]}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithCustomData(): void
    {
        $aps = new Aps(new Alert());
        $payload = new Payload($aps);

        $payload = $payload->withCustomData('some', 'key');
        $payload = $payload->withCustomData('foo', ['bar']);

        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"some":"key","foo":["bar"],"aps":{"alert":{"body":""}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithCustomApsData(): void
    {
        $aps     = new Aps(new Alert(), ['foo' => ['bar']]);
        $aps     = $aps->withCustomData('some', 'key');
        $payload = new Payload($aps);

        $encoded = $this->encoder->encode($payload);

        self::assertEquals('{"aps":{"alert":{"body":""},"foo":["bar"],"some":"key"}}', $encoded);
    }
}
