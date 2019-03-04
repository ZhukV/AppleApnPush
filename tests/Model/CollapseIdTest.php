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

use Apple\ApnPush\Model\CollapseId;
use PHPUnit\Framework\TestCase;

class CollapseIdTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate()
    {
        $collapseId = new CollapseId('some');

        self::assertEquals('some', $collapseId->getValue());
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The apns-collapse-id cannot be larger than 64 bytes.
     */
    public function shouldThrowExceptionIfValueIsInvalid()
    {
        new CollapseId(str_repeat('a', 65));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeValue()
    {
        $collapseId = new CollapseId("some");
        $collapseIdWithChangedValue = $collapseId->withValue('another');

        self::assertEquals('another', $collapseIdWithChangedValue->getValue());
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The apns-collapse-id cannot be larger than 64 bytes.
     */
    public function shouldThrowExceptionIfChangedValueIsInvalid()
    {
        $collapseId = new CollapseId("some");
        $collapseId->withValue(str_repeat('a', 65));
    }
}
