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

use Apple\ApnPush\Encoder\MessageEncoder;
use Apple\ApnPush\Model\ApsData;
use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Message;
use PHPUnit\Framework\TestCase;

class MessageEncoderTest extends TestCase
{
    /**
     * @var MessageEncoder
     */
    private $encoder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->encoder = new MessageEncoder();
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithOnlyCustomAlert()
    {
        $aps = new ApsData();
        $aps = $aps->withBodyCustom(['some' => 'foo']);

        $message = new Message($aps);
        $encoded = $this->encoder->encode($message);

        self::assertEquals('{"aps":{"alert":{"some":"foo"}}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithAlert()
    {
        $aps = new ApsData();
        $aps = $aps->withBody('some');

        $message = new Message($aps);
        $encoded = $this->encoder->encode($message);

        self::assertEquals('{"aps":{"alert":"some"}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithSound()
    {
        $aps = new ApsData();
        $aps = $aps->withSound('foo.acc');

        $message = new Message($aps);
        $encoded = $this->encoder->encode($message);

        self::assertEquals('{"aps":{"alert":"","sound":"foo.acc"}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithBadge()
    {
        $aps = new ApsData();
        $aps = $aps->withBadge(2);

        $message = new Message($aps);
        $encoded = $this->encoder->encode($message);

        self::assertEquals('{"aps":{"alert":"","badge":2}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithCategory()
    {
        $aps = new ApsData();
        $aps = $aps->withCategory('foo');

        $message = new Message($aps);
        $encoded = $this->encoder->encode($message);

        self::assertEquals('{"aps":{"alert":"","category":"foo"}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithContentAvailable()
    {
        $aps = new ApsData();
        $aps = $aps->withContentAvailable(true);

        $message = new Message($aps);
        $encoded = $this->encoder->encode($message);

        self::assertEquals('{"aps":{"alert":"","content-available":1}}', $encoded);
    }

    /**
     * @test
     */
    public function shouldSuccessEncodeWithCustomData()
    {
        $aps = new ApsData();
        $message = new Message($aps);
        $message = $message->withCustomData('key1', 'value1');
        $message = $message->withCustomData('key2', ['value2']);

        $encoded = $this->encoder->encode($message);

        self::assertEquals('{"key1":"value1","key2":["value2"],"aps":{"alert":""}}', $encoded);
    }
}
