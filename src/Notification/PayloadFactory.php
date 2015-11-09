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
 * Default payload factory
 */
class PayloadFactory implements PayloadFactoryInterface
{
    /**
     * Create payload hash for message
     *
     * @param MessageInterface $message
     *
     * @return string
     *
     * @throws SendException
     */
    public function createPayload(MessageInterface $message)
    {
        $payload = pack(
            'CNNnH*',
            1, // Command
            $message->getIdentifier(),
            $message->getExpires()->format('U'),
            32, // Token length
            $message->getDeviceToken()
        );

        $jsonData = $this->createJsonPayload($message);

        $payloadSize = strlen($jsonData);

        // Check payload size
        if ($payloadSize > 2048) {
            throw new SendException(SendException::ERROR_INVALID_PAYLOAD_SIZE, 1, $message->getIdentifier(), $message);
        }

        $payload .= pack('n', $payloadSize);
        $payload .= $jsonData;

        return $payload;
    }

    /**
     * Create JSON payload
     *
     * @param MessageInterface $message
     *
     * @return string
     */
    public function createJsonPayload(MessageInterface $message)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            return json_encode($message->getPayloadData(), JSON_FORCE_OBJECT ^ JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode($message->getPayloadData(), JSON_FORCE_OBJECT);
        }
    }
}
