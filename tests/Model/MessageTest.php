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

use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\Payload;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The custom data value should be a scalar or \JsonSerializable instance, but "stdClass" given.
     */
    public function shouldThrowExceptionIfTrySetInvalidCustomData()
    {
        $message = new Payload(new Aps(new Alert()));
        $message->withCustomData('some', new \stdClass());
    }
}
