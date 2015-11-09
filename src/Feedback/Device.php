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

use Apple\ApnPush\Exception\FeedbackException;

/**
 * Feedback message
 *
 * @author Ryan Martinsen <ryan@ryanware.com>
 */
class Device
{
    /**
     * @var integer
     */
    protected $timestamp;

    /**
     * @var integer
     */
    protected $tokenLength;

    /**
     * @var string
     */
    protected $deviceToken;

    /**
     * Construct
     *
     * @param string $data
     */
    public function __construct($data = null)
    {
        if ($data) {
            $this->unpack($data);
        }
    }

    /**
     * Unpacks the APNS data into the required fields
     *
     * @param string $data
     *
     * @return Device
     *
     * @throws FeedbackException
     */
    public function unpack($data)
    {
        if (false === ($token = unpack('N1timestamp/n1length/H*token', $data))) {
            throw new FeedbackException('Unpack feedback error');
        }

        $this->timestamp = $token['timestamp'];
        $this->tokenLength = $token['length'];
        $this->deviceToken = $token['token'];

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return integer
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Get token length
     *
     * @return integer
     */
    public function getTokenLength()
    {
        return $this->tokenLength;
    }

    /**
     * Get device token
     *
     * @return string
     */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }
}
