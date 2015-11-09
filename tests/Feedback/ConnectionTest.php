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
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test sandbox mode
     */
    public function testSandboxMode()
    {
        /** @var \Apple\ApnPush\Certificate\CertificateInterface $certificate */
        $certificate = $this->getMockForAbstractClass('Apple\ApnPush\Certificate\CertificateInterface');
        $connection = new Connection($certificate, false);

        $this->assertEquals(2196, $connection->getPort());
        $this->assertEquals('feedback.push.apple.com', $connection->getUrl());

        $connection = new Connection($certificate, true);

        $this->assertEquals(2196, $connection->getPort());
        $this->assertEquals('feedback.sandbox.push.apple.com', $connection->getUrl());
    }
}
