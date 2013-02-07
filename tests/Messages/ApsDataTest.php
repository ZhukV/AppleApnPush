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
        $this->assertTrue($aps instanceof PayloadDataInterface);
        $this->assertTrue($aps instanceof ApsDataInterface);
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

        $this->assertEquals($aps->getBody(), $body);
        $this->assertEquals($aps->getSound(), $sound);
        $this->assertEquals($aps->getBadge(), $badge);
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
        $this->assertEquals($aps->getBodyCustom(), array('foo' => 'bar'));

        $this->assertEquals($aps->getPayloadData(), array(
            'alert' => array('foo' => 'bar')
        ));


        try {
            $aps->setBodyLocalize('LOCALE_KEY', array('P1' => 'V1', 'P2' => 'V2'));
            $this->fail('Not control body localize. Body message already exists.');
        }
        catch (\LogicException $e) {
        }

        $aps = new ApsData;
        $aps->setBodyLocalize('LOCALE_KEY', array('P1' => 'V1', 'P2' => 'V2'));
        $this->assertEquals($aps->getPayloadData(), array(
            'alert' => array(
                'loc-key' => 'LOCALE_KEY',
                'loc-args' => array(
                    'V1',
                    'V2'
                )
            )
        ));
    }
}