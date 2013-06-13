<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Feedback;

use Apple\ApnPush\Connection\Feedback;

/**
 * Feedback service test
 */
class ServiceTest extends \Apple\ApnPush\TestCase
{
    /**
     * @var \RequestStream\Stream\Socket\SocketClient
     */
    protected $socketConnection;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->socketConnection = $this->getMock(
            'RequestStream\Stream\Socket\SocketClient',
            array('create', 'write', 'read', 'is', 'close', 'closeConnection')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        unset ($this->socketConnection);
    }

    public function testService()
    {
        // Create service
        $service = new Service;
        $connection = new Feedback(__FILE__, 'pass', false);
        $service->setConnection($connection);

        // Get socket connection mock
        $socketMock = $this->socketConnection;

        $socketMock->expects($this->any())
            ->method('is')
            ->will($this->returnValue(false));

        $socketMock->expects($this->once())
            ->method('create');

        $socketMock->expects($this->never())
            ->method('write');

        $socketMock->expects($this->once())
            ->method('read')
            ->with($this->equalTo(-1))
            ->will($this->returnValue($this->packData() . $this->packData() . $this->packData()));

        // Set socket mock to connection
        $this->setValueToProtected($connection, 'socketConnection', $socketMock);

        // Testing send message
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
