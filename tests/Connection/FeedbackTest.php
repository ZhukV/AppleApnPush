<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Connection;

/**
 * Feedback test
 */
class FeedbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Default test
     */
    public function testBase()
    {
        // Test with setter&getter
        $connection = new Feedback;
        $this->assertNull($connection->getCertificateFile());
        $this->assertNull($connection->getCertificatePassPhrase());
        $this->assertFalse($connection->getSandboxMode());

        $connection->setCertificateFile(__FILE__);
        $this->assertEquals(__FILE__, $connection->getCertificateFile());

        $connection->setCertificatePassPhrase('foo');
        $this->assertEquals('foo', $connection->getCertificatePassPhrase());

        $connection->setSandboxMode(true);
        $this->assertTrue($connection->getSandboxMode());

        // Test with constructor
        $connection = new Feedback(__FILE__, '11', true);
        $this->assertEquals(__FILE__, $connection->getCertificateFile());
        $this->assertEquals('11', $connection->getCertificatePassPhrase());
        $this->assertTrue($connection->getSandboxMode());

        // Test fail certificate file
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
        $connection = new Feedback;

        $this->assertEquals(2196, $connection->getConnectionPort());
        $this->assertEquals('feedback.push.apple.com', $connection->getConnectionUrl());

        $connection->setSandboxMode(true);

        $this->assertEquals(2196, $connection->getConnectionPort());
        $this->assertEquals('feedback.sandbox.push.apple.com', $connection->getConnectionUrl());
    }
}
