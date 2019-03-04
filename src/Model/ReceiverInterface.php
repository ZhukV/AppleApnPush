<?php

declare(strict_types = 1);

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Model;

/**
 * The receiver model.
 * Store device token and topic (application).
 */
interface ReceiverInterface
{
    /**
     * Set the token
     *
     * @param DeviceToken $token
     *
     * @return Receiver
     */
    public function withToken(DeviceToken $token) : Receiver;

    /**
     * Get token
     *
     * @return DeviceToken
     */
    public function getToken() : DeviceToken;

    /**
     * Set the topic
     *
     * @param String $topic
     *
     * @return Receiver
     */
    public function withTopic(string $topic) : Receiver;

    /**
     * Get topic
     *
     * @return string
     */
    public function getTopic() : string;
}
