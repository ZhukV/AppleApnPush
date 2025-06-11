<?php

declare(strict_types = 1);

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
     */
    public function shouldFailCreate(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid priority "123". Can be 5 or 10.');

        new Priority(123);
    }

    /**
     * @test
     */
    public function shouldSuccessCreateImmediately(): void
    {
        $priority = Priority::immediately();

        self::assertEquals(10, $priority->getValue());
    }

    /**
     * @test
     */
    public function shouldSuccessCreatePowerConsiderations(): void
    {
        $priority = Priority::powerConsiderations();

        self::assertEquals(5, $priority->getValue());
    }
}
