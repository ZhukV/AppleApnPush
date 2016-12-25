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

use Apple\ApnPush\Model\ApsData;
use PHPUnit\Framework\TestCase;

class ApsDataTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessSerializeAndUnserialize()
    {
        $apsData = new ApsData();
        $apsData = $apsData
            ->withBody('some')
            ->withBodyLocalize('some', ['key' => 'value'])
            ->withBadge(1)
            ->withSound('some.acc')
            ->withCategory('some')
            ->withContentAvailable(true);

        $serialized = serialize($apsData);
        $unserializedApsData = unserialize($serialized);

        self::assertEquals($apsData, $unserializedApsData);
    }
}
