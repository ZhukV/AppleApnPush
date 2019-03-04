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
        new ApnId('asd');
    }

    /**
     * @test
     */
    public function shouldSuccessCreateFromId()
    {
        $id = new ApnId('6c109fec-9123-11e6-ae22-56b6b6499611');

        self::assertEquals('6c109fec-9123-11e6-ae22-56b6b6499611', $id->getValue());
    }

    /**
     * @test
     */
    public function shouldSuccessChangeTitle()
    {
        $id = new ApnId('df109fec-9123-11e6-ae22-56b6b6499655');
        $idWithChangedValue = $id->withValue('6c109fec-9123-11e6-ae22-56b6b6499611');

        self::assertEquals('6c109fec-9123-11e6-ae22-56b6b6499611', $idWithChangedValue->getValue());
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid UUID identifier "asd".
     */
    public function shouldThrowsExceptionIfUpdateValueIsInvalid()
    {
        $id = new ApnId('asd');
        $id->withValue('asd');
    }
}
