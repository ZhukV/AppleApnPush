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
     * Test set device token
     */
    public function testSetDeviceToken()
    {
        $defaultMessage = new DefaultMessage;

        $defaultMessage->setDeviceToken(str_repeat('a1', 32));
        $this->assertEquals($defaultMessage->getDeviceToken(), str_repeat('a1', 32));

        try {
            // Not 64 charset
            $defaultMessage->setDeviceToken('aa');
            $this->fail('Not control device token size.');
        }
        catch (\InvalidArgumentException $e) {
        }

        try {
            $defaultMessage->setDeviceToken(str_repeat('z', 64));
            $this->fail('Not control device token pattern.');
        }
        catch (\InvalidArgumentException $e) {
        }
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