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
        $this->assertEquals($connection->getCertificateFile(), __FILE__);

        $connection->setCertificatePassPhrase('qq');
        $this->assertEquals($connection->getCertificatePassPhrase(), 'qq');

        $connection->setSandboxMode(TRUE);
        $this->assertTrue($connection->getSandboxMode());

        $connection = new Connection(__FILE__, '11', TRUE);
        $this->assertEquals($connection->getCertificateFile(), __FILE__);
        $this->assertEquals($connection->getCertificatePassPhrase(), '11');
        $this->assertTrue($connection->getSandboxMode());

        try {
            $connection->setCertificateFile(__FILE__ . '.aa');
            $this->fail('Not control certificate file not found.');
        }
        catch (\Exception $e) {
        }
    }

    /**
     * Test sanbox mode
     */
    public function testSandboxMode()
    {
        $connection = new Connection;

        $this->assertEquals($connection->getConnectionPort(), 2195);
        $this->assertEquals($connection->getConnectionUrl(), 'gateway.push.apple.com');

        $connection->setSandboxMode(TRUE);

        $this->assertEquals($connection->getConnectionPort(), 2195);
        $this->assertEquals($connection->getConnectionUrl(), 'gateway.sandbox.push.apple.com');
    }
}