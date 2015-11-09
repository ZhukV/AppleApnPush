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
 * Default iOS message
 */
class Message implements MessageInterface, \Serializable
{
    /**
     * @var string
     */
    protected $apsData;

    /**
     * @var string
     */
    protected $deviceToken;

    /**
     * @var array
     */
    protected $customData = array();

    /**
     * @var \DateTime $expire
     */
    protected $expires;

    /**
     * @var integer
     */
    protected $identifier;

    /**
     * Common extra data for serialization
     *
     * @var array
     */
    protected $extra;

    /**
     * Construct
     *
     * @param string $deviceToken
     * @param string $body
     */
    public function __construct($deviceToken = null, $body = null)
    {
        $this->apsData = new ApsData;
        $this->customData = array();
        // Default expires for ApnPush
        $this->expires = new \DateTime('+12 hours', new \DateTimeZone('UTC'));
        $this->identifier = 0;

        if (null !== $deviceToken) {
            $this->setDeviceToken($deviceToken);
        }

        if (null !== $body) {
            $this->setBody($body);
        }
    }

    /**
     * Set identifier. Attention: the identifier must be a integer value, and this value
     * cannot be larger than 4294967294 (32 bit unsigned long)!
     *
     * @param integer $identifier
     *
     * @return Message
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfRangeException
     */
    public function setIdentifier($identifier)
    {
        if (null !== $identifier) {
            if (!is_int($identifier)) {
                throw new \InvalidArgumentException(sprintf(
                    'The identifier must be a integer value, "%s" given',
                    gettype($identifier)
                ));
            }

            if ($identifier && $identifier > 4294967294) {
                throw new \OutOfRangeException(sprintf(
                    'Invalid identifier value. Cannot be larger than 4294967294, "%s" given.',
                    $identifier
                ));
            }
        }

        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return integer
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set device token
     *
     * @param string $deviceToken
     *
     * @return Message
     *
     * @throws \InvalidArgumentException
     */
    public function setDeviceToken($deviceToken)
    {
        if (is_object($deviceToken)) {
            if (!method_exists($deviceToken, '__toString')) {
                throw new \InvalidArgumentException(sprintf(
                    'Can\'t set device token from object "%s". Object must have __toString method.',
                    get_class($deviceToken)
                ));
            }

            $deviceToken = (string) $deviceToken;
        }

        if (!is_string($deviceToken)) {
            throw new \InvalidArgumentException(sprintf(
                'Device token must be a string, "%s" given.',
                gettype($deviceToken)
            ));
        }

        if (preg_match('/[^0-9a-fA-F]/', $deviceToken)) {
            throw new \InvalidArgumentException(sprintf(
                'Device token must be mask "%s". Device token: "%s"',
                '/[^0-9a-fA-F]/',
                $deviceToken
            ));
        }

        if (strlen($deviceToken) != 64) {
            throw new \InvalidArgumentException(sprintf(
                'Device token must be a 64 characters, "%d".',
                strlen($deviceToken)
            ));
        }

        $this->deviceToken = $deviceToken;

        return $this;
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

    /**
     * Set aps data
     *
     * @param ApsDataInterface $apsData
     *
     * @return Message
     */
    public function setApsData(ApsDataInterface $apsData)
    {
        $this->apsData = $apsData;

        return $this;
    }

    /**
     * Get aps data
     *
     * @return ApsDataInterface
     */
    public function getApsData()
    {
        return $this->apsData;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Message
     */
    public function setBody($body)
    {
        $this->apsData->setBody($body);

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->apsData->getBody();
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Message
     */
    public function setCategory($category)
    {
        $this->apsData->setCategory($category);

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->apsData->getCategory();
    }

    /**
     * Set body localize
     *
     * @param string $localizeKey
     * @param array  $params
     *
     * @return Message
     */
    public function setBodyLocalize($localizeKey, array $params = array())
    {
        $this->apsData->setBodyLocalize($localizeKey, $params);

        return $this;
    }

    /**
     * Set custom data
     *
     * @param array $customData
     *
     * @return Message
     */
    public function setCustomData(array $customData)
    {
        $this->customData = array();

        foreach ($customData as $key => $value) {
            $this->addCustomData($key, $value);
        }

        return $this;
    }

    /**
     * Add custom data
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Message
     */
    public function addCustomData($key, $value = null)
    {
        if (!is_array($key)) {
            $key = array($key => $value);
        }

        foreach ($key as $k => $v) {
            $this->customData[$k] = $v;
        }

        return $this;
    }

    /**
     * Get custom data
     *
     * @return array
     */
    public function getCustomData()
    {
        return $this->customData;
    }

    /**
     * Set expires of this message
     *
     * @param \DateTime $expires
     *
     * @return Message
     */
    public function setExpires(\DateTime $expires)
    {
        $value = clone $expires;

        if ($value->getTimezone()->getName() != 'UTC') {
            $value->setTimezone(new \DateTimeZone('UTC'));
        }

        $this->expires = $value;

        return $this;
    }

    /**
     * Get expires
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Get payload data
     *
     * @return array
     */
    public function getPayloadData()
    {
        $this->preparePayload();

        return array(
            'aps' => $this->apsData->getPayloadData()
        ) + $this->customData;
    }

    /**
     * Set badge
     *
     * @param int $badge
     *
     * @return Message
     */
    public function setBadge($badge)
    {
        $this->apsData->setBadge($badge);

        return $this;
    }

    /**
     * Get madge
     *
     * @return int
     */
    public function getBadge()
    {
        return $this->apsData->getBadge();
    }

    /**
     * Set sound
     *
     * @param string $sound
     *
     * @return Message
     */
    public function setSound($sound)
    {
        $this->apsData->setSound($sound);

        return $this;
    }

    /**
     * Get sound
     *
     * @return string
     */
    public function getSound()
    {
        return $this->apsData->getSound();
    }

    /**
     * Set content available
     *
     * @param bool $contentAvailable
     *
     * @return Message
     */
    public function setContentAvailable($contentAvailable)
    {
        $this->apsData->setContentAvailable($contentAvailable);

        return $this;
    }

    /**
     * Is content available
     *
     * @return bool
     */
    public function isContentAvailable()
    {
        return $this->apsData->isContentAvailable();
    }

    /**
     * Set extra data for serialization
     *
     * @param array $extra
     *
     * @return Message
     */
    public function setExtra(array $extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra for serialization
     *
     * @param string $key
     * @param mixed  $defaultValue
     *
     * @return array
     */
    public function getExtra($key = null, $defaultValue = null)
    {
        if (null === $key) {
            return $this->extra;
        }

        return isset($this->extra[$key]) ? $this->extra[$key] : $defaultValue;
    }

    /**
     * Prepare payload
     */
    protected function preparePayload()
    {
    }

    /**
     * Serialize message
     *
     * @return string
     */
    public function serialize()
    {
        $this->preparePayload();

        $data = array(
            'aps_data' => $this->apsData,
            'device_token' => $this->deviceToken,
            'custom_data' => $this->customData,
            'expires' => $this->expires->format(\DateTime::ISO8601),
            'identifier' => $this->identifier,
            'extra' => $this->extra
        );

        return serialize($data);
    }

    /**
     * Unserialize message
     *
     * @param string $data
     */
    public function unserialize($data)
    {
        $data = unserialize($data);

        if ($data['aps_data']) {
            $this->setApsData($data['aps_data']);
        }

        if ($data['device_token']) {
            $this->setDeviceToken($data['device_token']);
        }

        if ($data['custom_data']) {
            $this->setCustomData($data['custom_data']);
        }

        if ($data['expires']) {
            $this->setExpires(\DateTime::createFromFormat(\DateTime::ISO8601, $data['expires']));
        }

        if ($data['identifier']) {
            $this->setIdentifier($data['identifier']);
        }

        if ($data['extra']) {
            $this->setExtra($data['extra']);
        }
    }
}
