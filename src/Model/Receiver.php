<?php

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
class Receiver
{
    /**
     * @var DeviceToken
     */
    private $token;

    /**
     * @var string
     */
    private $topic;

    /**
     * Constructor.
     *
     * @param DeviceToken $token
     * @param string      $topic
     */
    public function __construct(DeviceToken $token, string $topic)
    {
        $this->token = $token;
        $this->topic = $topic;
    }

    /**
     * Get token
     *
     * @return DeviceToken
     */
    public function getToken() : DeviceToken
    {
        return $this->token;
    }

    /**
     * Get topic
     *
     * @return string
     */
    public function getTopic() : string
    {
        return $this->topic;
    }
}
