<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\PayloadFactory;

use Apple\ApnPush\Messages\DefaultMessage;

/**
 * Payload factory test
 */
class PayloadFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base test
     */
    public function testBase()
    {
        $payloadFactory = new PayloadFactory;
        $message = new DefaultMessage;
        $message->setBody('test');
        $message->setIdentifier(1);
        $message->setDeviceToken(str_repeat('a', 64));

        $this->assertNotNull($payloadFactory->createPayload($message));
    }
}