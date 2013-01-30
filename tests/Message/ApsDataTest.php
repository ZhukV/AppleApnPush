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

        $aps->setBody('Hello');
        $this->assertEquals($aps->getBody(), 'Hello');

        $aps->setSound('1.mp3');
        $this->assertEquals($aps->getSound(), '1.mp3');

        $aps->setBadge(5);
        $this->assertEquals($aps->getBadge(), 5);

        $this->assertEquals($aps->getPayloadData(), array(
            'alert' => 'Hello',
            'sound' => '1.mp3',
            'badge' => 5
        ));
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