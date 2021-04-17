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

use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Receiver;
use PHPUnit\Framework\TestCase;

class ReceiverTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate(): void
    {
        $receiver = new Receiver(new DeviceToken('4e064d251c73cca4096b82b3fbe9abd05d239f96b91a09edb61c92322bf959ca'), 'my.topic');

        self::assertEquals(new DeviceToken('4e064d251c73cca4096b82b3fbe9abd05d239f96b91a09edb61c92322bf959ca'), $receiver->getToken());
        self::assertEquals('my.topic', $receiver->getTopic());
    }
}
