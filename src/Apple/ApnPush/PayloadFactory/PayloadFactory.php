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

use Apple\ApnPush\Messages\MessageInterface;

/**
 * Default payload factory
 */
class PayloadFactory implements PayloadFactoryInterface
{
    /**
     * @{inerhitDoc}
     */
    public function createPayload(MessageInterface $message)
    {
        $payload = pack('CNNnH*',
            1, // Command
            $message->getIdentifier(),
            $message->getExpires()->format('U'),
            32, // Token length
            $message->getDeviceToken()
        );

        $jsonData = $this->createJsonPayload($message);

        $payload .= pack('n', mb_strlen($jsonData));
        $payload .= $jsonData;

        return $payload;
    }

    /**
     * @{inerhitDoc}
     */
    public function createJsonPayload(MessageInterface $message)
    {
        return json_encode($message->getPayloadData(), JSON_FORCE_OBJECT);
    }
}