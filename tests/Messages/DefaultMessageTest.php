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
        $this->assertTrue($defaultMessage instanceof MessageInterface);
        $this->assertTrue($defaultMessage instanceof PayloadDataInterface);
        $this->assertTrue($defaultMessage->getApsData() instanceof ApsDataInterface);
        $this->assertTrue($defaultMessage->getExpires() instanceof \DateTime);
        $this->assertEquals($defaultMessage->getIdentifier(), 0);

        $defaultMessage->setIdentifier(123);
        $this->assertEquals($defaultMessage->getIdentifier(), 123);
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

        $this->assertEquals($defaultMessage->getCustomData(), array());

        $customData = array(
            'c1' => 'v1'
        );
        $defaultMessage->setCustomData($customData);

        $this->assertEquals($defaultMessage->getCustomData(), $customData);
        $defaultMessage->addCustomData('c2', 'v2');
        $this->assertEquals($defaultMessage->getCustomData(), $customData + array('c2' => 'v2'));
    }

    /**
     * Test aps data
     */
    public function testApsData()
    {
        $defaultMessage = new DefaultMessage;
        $apsData = new ApsData;
        $defaultMessage->setApsData($apsData);
        $this->assertEquals($defaultMessage->getApsData(), $apsData);
    }
}