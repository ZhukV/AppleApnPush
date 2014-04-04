<?php

/**
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
     * @dataProvider deviceTokenProvider
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
            ->setIdentifier('foo')
            ->setDeviceToken(str_repeat('af', 32));

        $serializeData = serialize($message);
        /** @var Message $newMessage */
        $newMessage = unserialize($serializeData);

        $this->assertEquals(array('foo' => 'bar'), $newMessage->getCustomData());
        $this->assertEquals('foo', $newMessage->getIdentifier());
        $this->assertEquals(str_repeat('af', 32), $newMessage->getDeviceToken());
        $this->assertInstanceOf('Apple\ApnPush\Notification\ApsDataInterface', $newMessage->getApsData());
        $this->assertInstanceOf('DateTime', $newMessage->getExpires());
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