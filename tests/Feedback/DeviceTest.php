<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Feedback;

/**
 * Feedback Device test
 *
 * @author Ryan Martinsen <ryan@ryanware.com>
 *
 * @todo: add test for control exceptions
 */
class DeviceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base test
     */
    public function testBase()
    {
        $timestamp   = time();
        $tokenLength = 32;
        $deviceToken = str_repeat('14', 32);
        
        $data = pack('NnH*', $timestamp, $tokenLength, $deviceToken);

        $device = new Device($data);

        $this->assertEquals($timestamp, $device->getTimestamp());
        $this->assertEquals($tokenLength, $device->getTokenLength());
        $this->assertEquals($deviceToken, $device->getDeviceToken());
    }
}
