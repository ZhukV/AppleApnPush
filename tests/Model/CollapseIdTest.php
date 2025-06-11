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

use Apple\ApnPush\Model\CollapseId;
use PHPUnit\Framework\TestCase;

class CollapseIdTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate(): void
    {
        $collapseId = new CollapseId('some');

        self::assertEquals('some', $collapseId->getValue());
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfValueIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The apns-collapse-id cannot be larger than 64 bytes.');

        new CollapseId(str_repeat('a', 65));
    }
}
