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

use Apple\ApnPush\Model\ApnId;
use PHPUnit\Framework\TestCase;

class ApnIdTest extends TestCase
{
    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid UUID identifier "asd".
     */
    public function shouldThrowsExceptionIfIdIsInvalid()
    {
        ApnId::fromId('asd');
    }

    /**
     * @test
     */
    public function shouldSuccessCreateFromId()
    {
        $id = ApnId::fromId('6c109fec-9123-11e6-ae22-56b6b6499611');

        self::assertEquals('6c109fec-9123-11e6-ae22-56b6b6499611', $id->getValue());
    }

    /**
     * @test
     */
    public function shouldSuccessCreateNullId()
    {
        $id = ApnId::fromNull();

        self::assertTrue($id->isNull());
    }

    /**
     * @test
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Can not get value from null Apn ID object.
     */
    public function shouldThrowExceptionInGetValueIfIdIsNull()
    {
        $id = ApnId::fromNull();

        $id->getValue();
    }
}
