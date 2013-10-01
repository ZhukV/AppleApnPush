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
 * Test aps data
 */
class ApsDataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base test
     */
    public function testBase()
    {
        $aps = new ApsData;
        $this->assertInstanceOf('Apple\ApnPush\Notification\PayloadDataInterface', $aps);
    }

    /**
     * @dataProvider apsDataProvider
     */
    public function testApsData($body, $sound, $badge)
    {
        $aps = new ApsData;

        $aps->setBody($body);
        $aps->setSound($sound);
        $aps->setBadge($badge);

        $this->assertEquals($body, $aps->getBody());
        $this->assertEquals($sound, $aps->getSound());
        $this->assertEquals($badge, $aps->getBadge());
    }

    /**
     * Provider for test ApsData
     */
    public function apsDataProvider()
    {
        return array(
            array(null, null, null),
            array('foo', null, null),
            array(null, 'foo.mp3', null),
            array(null, null, 4),
            array('foo', 'bar.mp3', null),
            array(null, 'bar.mp3', 5),
            array('foo', null, 3),
            array('foo', 'bar.mp3', 20)
        );
    }

    /**
     * Test custom body
     */
    public function testCustomBody()
    {
        $aps = new ApsData;
        $aps->setBody('Hello');

        $aps->setBodyCustom(array('foo' => 'bar'));
        $this->assertEquals(array('foo' => 'bar'), $aps->getBodyCustom());

        $this->assertEquals(array(
            'alert' => array('foo' => 'bar')
        ),$aps->getPayloadData());

        try {
            $aps->setBodyLocalize('LOCALE_KEY', array('P1' => 'V1', 'P2' => 'V2'));
            $this->fail('Not control body localize. Body message already exists.');
        } catch (\LogicException $e) {
        }

        $aps = new ApsData;
        $aps->setBodyLocalize('LOCALE_KEY', array('P1' => 'V1', 'P2' => 'V2'));
        $this->assertEquals(array(
            'alert' => array(
                'loc-key' => 'LOCALE_KEY',
                'loc-args' => array(
                    'V1',
                    'V2'
                )
            )
        ), $aps->getPayloadData());
    }

    /**
     * Test serialize
     */
    public function testSerialize()
    {
        $aps = new ApsData();
        $aps
            ->setBody('foo')
            ->setBodyCustom(array('bar' => 'foo'))
            ->setSound('test.acc')
            ->setBadge(5);

        $serializeData = serialize($aps);
        /** @var ApsData $newAps */
        $newAps = unserialize($serializeData);

        $this->assertEquals('foo', $newAps->getBody());
        $this->assertEquals(array('bar' => 'foo'), $newAps->getBodyCustom());
        $this->assertEquals('test.acc', $newAps->getSound());
        $this->assertEquals(5, $newAps->getBadge());
    }
}