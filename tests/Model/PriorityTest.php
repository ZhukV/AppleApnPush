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

use Apple\ApnPush\Model\Priority;
use PHPUnit\Framework\TestCase;

class PriorityTest extends TestCase
{
    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid priority "123". Can be 5 or 10.
     */
    public function shouldFailCreate()
    {
        new Priority(123);
    }

    /**
     * @test
     */
    public function shouldSuccessCreateImmediately()
    {
        $priority = Priority::immediately();

        self::assertEquals(10, $priority->getValue());
    }

    /**
     * @test
     */
    public function shouldSuccessCreatePowerConsiderations()
    {
        $priority = Priority::powerConsiderations();

        self::assertEquals(5, $priority->getValue());
    }

    /**
     * @test
     */
    public function shouldSuccessChangeValue()
    {
        $priority = new Priority(5);
        $priorityChangedValue = $priority->withValue(10);

        self::assertEquals(10, $priorityChangedValue->getValue());
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid priority "45". Can be 5 or 10.
     */
    public function shouldThrowExceptionIfChangedValueIsInvalid()
    {
        $priority = new Priority(5);
        $priority->withValue(45);
    }
}
