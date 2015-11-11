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
 * Feedback test
 *
 * @author Ryan Martinsen <ryan@ryanware.com>
 */
class FeedbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Apple\ApnPush\Feedback\Connection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $connection;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->connection = $this->getMock(
            'Apple\ApnPush\Feedback\Connection',
            array('is', 'write', 'isReadyRead', 'connect', 'close', 'read'),
            array(),
            '',
            false
        );
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        unset ($this->connection);
    }

    /**
     * Base feedback test
     */
    public function testService()
    {
        $this->connection->expects($this->any())->method('is')
            ->will($this->returnValue(false));

        $this->connection->expects($this->once())->method('connect');

        $this->connection->expects($this->never())->method('write');

        $this->connection->expects($this->once())->method('read')
            ->with($this->equalTo(-1))
            ->will($this->returnValue($this->packData() . $this->packData() . $this->packData()));

        // Create service
        $service = new Feedback();
        $service->setConnection($this->connection);

        // Testing get invalid devices
        $devices = $service->getInvalidDevices();
        $this->assertNotEmpty($devices);
        $this->assertEquals(3, count($devices));
    }

    /**
     * Create binary string feedback data.
     */
    public static function packData($deviceToken = null)
    {
        $timestamp   = time();
        $tokenLength = 32;
        $deviceToken = $deviceToken ? $deviceToken : str_repeat('af', 32);
        
        return pack('NnH*', $timestamp, $tokenLength, $deviceToken);
    }
}
