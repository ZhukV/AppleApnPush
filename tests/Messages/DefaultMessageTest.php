<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Messages;

use Apple\ApnPush\PayloadFactory\PayloadDataInterface;

/**
 * Default message test
 */
class DefaultMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base test
     */
    public function testBase()
    {
        $defaultMessage = new DefaultMessage;
        $this->assertInstanceOf('Apple\ApnPush\PayloadFactory\PayloadDataInterface', $defaultMessage);
        $this->assertInstanceOf('Apple\ApnPush\Messages\ApsDataInterface', $defaultMessage->getApsData());
        $this->assertInstanceOf('DateTime', $defaultMessage->getExpires());
        $this->assertEquals(0, $defaultMessage->getIdentifier());

        $defaultMessage->setIdentifier(123);
        $this->assertEquals(123, $defaultMessage->getIdentifier());
    }

    /**
     * @dataProvider deviceTokenProvider
     */
    public function testSetDeviceToken($token, $exception)
    {
        if ($exception) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $defaultMessage = new DefaultMessage;

        $defaultMessage->setDeviceToken($token);
        $this->assertEquals($defaultMessage->getDeviceToken(), $token);
    }

    /**
     * Provider for testing device token
     */
    public function deviceTokenProvider()
    {
        return array(
            array('foo_bar', true),
            array(str_repeat('aq', 32), true),
            array(str_repeat('af', 32), false)
        );
    }

    /**
     * Custom data test
     */
    public function testCustomData()
    {
        $defaultMessage = new DefaultMessage;

        $this->assertEquals(array(), $defaultMessage->getCustomData());

        $customData = array('c1' => 'v1');
        $defaultMessage->setCustomData($customData);

        $this->assertEquals($customData, $defaultMessage->getCustomData());
        $defaultMessage->addCustomData('c2', 'v2');
        $this->assertEquals($customData + array('c2' => 'v2'), $defaultMessage->getCustomData());
    }

    /**
     * Test aps data
     */
    public function testApsData()
    {
        $defaultMessage = new DefaultMessage;
        $apsData = new ApsData;
        $defaultMessage->setApsData($apsData);
        $this->assertEquals($apsData, $defaultMessage->getApsData());
    }
}