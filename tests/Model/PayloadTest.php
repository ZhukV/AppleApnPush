<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Model;

use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\Payload;
use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate()
    {
        $payload = new Payload(new Aps(new Alert()));

        self::assertEquals([], $payload->getCustomData());
        self::assertEquals(new Aps(new Alert()), $payload->getAps());
    }

    /**
     * @test
     */
    public static function shouldSuccessCreateWithBody()
    {
        $payload = Payload::createWithBody('some');

        self::assertEquals(new Payload(new Aps(new Alert('some'))), $payload);
    }

    /**
     * @test
     */
    public function shouldSuccessChangeAps()
    {
        $payload = new Payload(new Aps(new Alert()));
        $payloadWithChangedAps = $payload->withAps(new Aps(new Alert('some')));

        self::assertEquals(new Aps(new Alert('some')), $payloadWithChangedAps->getAps());
        self::assertNotEquals(spl_object_hash($payload), spl_object_hash($payloadWithChangedAps));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeCustomData()
    {
        $payload = new Payload(new Aps(new Alert()));
        $payloadWithChangedCustomData = $payload->withCustomData('some', 'value');

        self::assertEquals(['some' => 'value'], $payloadWithChangedCustomData->getCustomData());
        self::assertNotEquals(spl_object_hash($payload), spl_object_hash($payloadWithChangedCustomData));
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The custom data value should be a scalar or \JsonSerializable instance, but "stdClass" given.
     */
    public function shouldThrowExceptionIfUsingWrongCustomDataValueOnConstruction()
    {
        new Payload(new Aps(new Alert()), ['some-key' => new \stdClass()]);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The custom data key should be a string, but "integer" given.
     */
    public function shouldThrowExceptionIfUsingWrongCustomDataKeyOnConstruction()
    {
        new Payload(new Aps(new Alert()), [0 => 'test']);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The custom data value should be a scalar or \JsonSerializable instance, but "stdClass" given.
     */
    public function shouldThrowExceptionIfTrySetInvalidCustomData()
    {
        $payload = new Payload(new Aps(new Alert()));
        $payload->withCustomData('some', new \stdClass());
    }
}
