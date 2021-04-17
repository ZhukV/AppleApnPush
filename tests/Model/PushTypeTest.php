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

use Apple\ApnPush\Model\PushType;
use PHPUnit\Framework\TestCase;

class PushTypeTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate(): void
    {
        self::assertEquals(PushType::TYPE_ALERT, (string) PushType::alert());
        self::assertEquals(PushType::TYPE_BACKGROUND, (string) PushType::background());
    }
}
