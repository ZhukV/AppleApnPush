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

use Apple\ApnPush\Model\Expiration;
use PHPUnit\Framework\TestCase;

class ExpirationTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnZeroIfNotStore(): void
    {
        $expiration = new Expiration();
        $value = $expiration->getValue();

        self::assertEquals(0, $value);
    }

    /**
     * @test
     */
    public function shouldSuccessCreate(): void
    {
        $now = new \DateTime();
        $expiration = new Expiration($now);

        $value = $expiration->getValue();

        self::assertEquals($now->format('U'), $value);
    }
}
