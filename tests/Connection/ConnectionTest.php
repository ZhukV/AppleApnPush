<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Connection;

/**
 * Connection test
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Default test
     */
    public function testBase()
    {
        $connection = new Connection;
        $this->assertTrue($connection instanceof ConnectionInterface);
        $this->assertNull($connection->getCertificateFile());
        $this->assertNull($connection->getCertificatePassPhrase());
        $this->assertFalse($connection->getSandboxMode());

        $connection->setCertificateFile(__FILE__);
        $this->assertEquals(__FILE__, $connection->getCertificateFile());

        $connection->setCertificatePassPhrase('qq');
        $this->assertEquals('qq', $connection->getCertificatePassPhrase());

        $connection->setSandboxMode(true);
        $this->assertTrue($connection->getSandboxMode());

        $connection = new Connection(__FILE__, '11', true);
        $this->assertEquals(__FILE__, $connection->getCertificateFile());
        $this->assertEquals('11', $connection->getCertificatePassPhrase());
        $this->assertTrue($connection->getSandboxMode());

        try {
            $connection->setCertificateFile(__FILE__ . '.aa');
            $this->fail('Not control certificate file not found.');
        } catch (\Exception $e) {
        }
    }

    /**
     * Test sanbox mode
     */
    public function testSandboxMode()
    {
        $connection = new Connection;

        $this->assertEquals(2195, $connection->getConnectionPort());
        $this->assertEquals('gateway.push.apple.com', $connection->getConnectionUrl());

        $connection->setSandboxMode(true);

        $this->assertEquals(2195, $connection->getConnectionPort());
        $this->assertEquals('gateway.sandbox.push.apple.com', $connection->getConnectionUrl());
    }

    /**
     * Test connection
     */
    public function testConnection()
    {
        $connection = new Connection(__FILE__);

        $mock = $this->getMock(
            'RequestStream\\Stream\\Socket\\SocketClient',
            array('create', 'write', 'read', 'selectRead', 'setBlocking', 'is')
        );

        // Replace create
        $mock->expects($this->once())->method('create')->will($this->returnCallback(function() use ($mock) {
            $mock->__Create__ = true;
        }));

        // Replace write
        $mock->expects($this->any())->method('write')->will($this->returnCallback(function($text, $size = null) {
            return mb_strlen($text);
        }));

        // Replace is method
        $mock->expects($this->any())->method('is')->will($this->returnCallback(function($autload = false) use ($mock) {
            return !empty($mock->__Create__);
        }));

        // Read
        $mock->expects($this->any())->method('read')->will($this->returnCallback(function($length) {
            return str_repeat('a', $length);
        }));

        // Select blocking
        $mock->expects($this->any())->method('selectRead')->will($this->returnValue(false));

        $refSocket = new \ReflectionProperty($connection, 'socketConnection');
        $refSocket->setAccessible(true);
        $refSocket->setValue($connection, $mock);

        $this->assertFalse($connection->isConnection());
        $connection->createConnection();
        $this->assertTrue($connection->isConnection());

        $this->assertEquals(4, $connection->write('test'));
        $this->assertEquals('a', $connection->read(1));
        $this->assertEquals('aaa', $connection->read(3));
        $this->assertFalse($connection->isReadyRead());
    }
}