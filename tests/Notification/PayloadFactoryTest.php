<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

/**
 * Payload factory test
 */
class PayloadFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerPayloadFactory
     */
    public function testPayloadFactory($identifier, $expires, $token, $body)
    {
        $message = new Message();
        $message
            ->setIdentifier($identifier)
            ->setExpires($expires)
            ->setDeviceToken($token)
            ->setBody($body);

        $payload = new PayloadFactory;

        $this->assertNotNull($payload->createPayload($message));

        $jsonData = json_encode($message->getPayloadData(), JSON_FORCE_OBJECT);

        $payloadEqData = pack('CNNnH*',
            1,
            $identifier,
            $expires->format('U'),
            32,
            $token
        ) . pack('n', mb_strlen($jsonData)) . $jsonData;

        $this->assertEquals($payloadEqData, $payload->createPayload($message));
    }

    /**
     * Provider for test PayloadFactory
     */
    public function providerPayloadFactory()
    {
        return array(
            array(0, new \DateTime(), str_repeat('af', 32), 'foo'),
            array(256, new \DateTime('+12 hours'), str_repeat('ab', 32), 'bar'),
            array(11111, new \DateTime('-12 hours'), str_repeat('ae', 32), 'foo_bar')
        );
    }
}