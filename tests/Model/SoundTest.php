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

use Apple\ApnPush\Model\Sound;
use PHPUnit\Framework\TestCase;

class SoundTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate(): void
    {
        $sound = new Sound('foo', 0.5, true);

        self::assertEquals('foo', $sound->getName());
        self::assertEquals(0.5, $sound->getVolume());
        self::assertTrue($sound->isCritical());
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfVolumeLessThenZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid volume -0.01. Must be between 0.0 and 1.0');

        new Sound('foo', -0.01);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfVolumeMoreThenZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid volume 1.01. Must be between 0.0 and 1.0');

        new Sound('foo', 1.01);
    }
}
