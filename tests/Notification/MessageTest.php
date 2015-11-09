<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

/**
 * Message message test
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base test
     */
    public function testBase()
    {
        $message = new Message();
        $this->assertInstanceOf('Apple\ApnPush\Notification\PayloadDataInterface', $message);
        $this->assertInstanceOf('Apple\ApnPush\Notification\ApsDataInterface', $message->getApsData());
        $this->assertInstanceOf('DateTime', $message->getExpires());
        $this->assertEquals(0, $message->getIdentifier());

        $message->setIdentifier(123);
        $this->assertEquals(123, $message->getIdentifier());
    }

    /**
     * Test set device token
     *
     * @dataProvider providerDeviceToken
     */
    public function testSetDeviceToken($token, $exception)
    {
        if ($exception) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $message = new Message();

        $message->setDeviceToken($token);
        $this->assertEquals($message->getDeviceToken(), $token);
    }

    /**
     * Provider for testing device token
     */
    public function providerDeviceToken()
    {
        return array(
            array('foo_bar', true),
            array(str_repeat('aq', 32), true),
            array(str_repeat('af', 32), false),
            array(str_repeat('AF', 32), false)
        );
    }

    /**
     * Set set message identifier
     *
     * @dataProvider providerIdentifier
     */
    public function testSetIdentifier($identifier, $invalid, $outOfRange)
    {
        if ($invalid) {
            $this->setExpectedException('InvalidArgumentException');
        } elseif ($outOfRange) {
            $this->setExpectedException('OutOfRangeException');
        }

        $message = new Message();

        $message->setIdentifier($identifier);
        $this->assertEquals($message->getIdentifier(), $identifier);
    }

    /**
     * Provider for testing identifier
     */
    public function providerIdentifier()
    {
        return array(
            array(array(), true, false),
            array((object) array(), true, false),
            array('foo-bar', true, false),
            array('1234', true, false),
            array(123456789, false, false), // Correct
            array(4294967295, false, true)
        );
    }

    /**
     * Test set expires
     */
    public function testSetExpires()
    {
        $message = new Message();

        $expires = new \DateTime('now', new \DateTimeZone('Europe/Paris'));

        $message->setExpires($expires);

        $this->assertEquals('Europe/Paris', $expires->getTimezone()->getName());
        $this->assertEquals('UTC', $message->getExpires()->getTimezone()->getName());
    }

    /**
     * Custom data test
     */
    public function testCustomData()
    {
        $message = new Message();

        $this->assertEquals(array(), $message->getCustomData());

        $customData = array('c1' => 'v1');
        $message->setCustomData($customData);

        $this->assertEquals($customData, $message->getCustomData());
        $message->addCustomData('c2', 'v2');
        $this->assertEquals($customData + array('c2' => 'v2'), $message->getCustomData());
    }

    /**
     * Test aps data
     */
    public function testApsData()
    {
        $message = new Message();
        $apsData = new ApsData;
        $message->setApsData($apsData);
        $this->assertEquals($apsData, $message->getApsData());
    }

    /**
     * Test serialize
     */
    public function testSerialize()
    {
        $message = new Message();
        $message
            ->setCustomData(array('foo' => 'bar'))
            ->setIdentifier(12345)
            ->setDeviceToken(str_repeat('af', 32))
            ->setExtra(array('foo' => 'bar'));

        $serializeData = serialize($message);
        /** @var Message $newMessage */
        $newMessage = unserialize($serializeData);

        $this->assertEquals(array('foo' => 'bar'), $newMessage->getCustomData());
        $this->assertEquals(12345, $newMessage->getIdentifier());
        $this->assertEquals(str_repeat('af', 32), $newMessage->getDeviceToken());
        $this->assertInstanceOf('Apple\ApnPush\Notification\ApsDataInterface', $newMessage->getApsData());
        $this->assertInstanceOf('DateTime', $newMessage->getExpires());
        $this->assertEquals(array('foo' => 'bar'), $newMessage->getExtra());
    }

    /**
     * Test content available
     */
    public function testContentAvailable()
    {
        $message = new Message();
        $this->assertNull($message->isContentAvailable());
        
        $message->setContentAvailable(true);
        $this->assertTrue($message->isContentAvailable());
        
        $message->setContentAvailable(false);
        $this->assertFalse($message->isContentAvailable());
    }
}