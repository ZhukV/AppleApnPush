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
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Can not get value of expiration from null value.
     */
    public function shouldThrowExceptionInGetValueIfExpirationIsNull()
    {
        $expiration = Expiration::fromNull();
        $expiration->getValue();
    }

    /**
     * @test
     */
    public function shouldReturnZeroIfNotStore()
    {
        $expiration = Expiration::notStore();
        $value = $expiration->getValue();

        self::assertEquals(0, $value);
    }

    /**
     * @test
     */
    public function shouldSuccessGetValue()
    {
        $now = new \DateTime();
        $expiration = Expiration::storeTo($now);

        $value = $expiration->getValue();

        self::assertEquals($now->format('U'), $value);
    }
}
