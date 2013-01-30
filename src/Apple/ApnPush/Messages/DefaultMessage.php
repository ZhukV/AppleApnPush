<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Messages;

/**
 * Default iOS message
 */
class DefaultMessage implements MessageInterface
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
    protected $customData;

    /**
     * @var \DateTime $expire
     */
    protected $expires;

    /**
     * @var integer
     */
    protected $identifier;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->apsData = new ApsData;
        $this->customData = array();
        $this->expires = new \DateTime('+12 hours', new \DateTimeZone('UTC'));
        $this->identifier = 0;
    }

    /**
     * @{inerhitDoc}
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @{inerhitDoc}
     */
    public function setDeviceToken($deviceToken)
    {
        if (is_object($deviceToken)) {
            if (!method_exists($deviceToken, '__toString')) {
                throw new \InvalidArgumentException(sprintf('Can\'t set device token from object "%s".', get_class($deviceToken)));
            }

            $deviceToken = (string) $deviceToken;
        }

        if (!is_string($deviceToken)) {
            throw new \InvalidArgumentException(sprintf(
                'Device token must be a string, "%s" given.',
                gettype($deviceToken)
            ));
        }

        if (preg_match('/[^0-9a-f]/', $deviceToken)) {
            throw new \InvalidArgumentException(sprintf(
                'Device token must be mask "%s". Device token: "%s"',
                '/[^0-9a-f]/',
                $deviceToken
            ));
        }

        if (mb_strlen($deviceToken) != 64) {
            throw new \InvalidArgumentException(sprintf(
                'Device token must be a 64 charsets, "%d".',
                mb_strlen($deviceToken)
            ));
        }

        $this->deviceToken = $deviceToken;

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }

    /**
     * @{inerhitDoc}
     */
    public function setApsData(ApsDataInterface $apsData)
    {
        $this->apsData = $apsData;

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getApsData()
    {
        return $this->apsData;
    }

    /**
     * @{inerhitDoc}
     */
    public function setBody($body)
    {
        $this->apsData->setBody($body);

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getBody()
    {
        return $this->apsData->getBody();
    }

    /**
     * @{inerhitDoc}
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
     * @{inerhitDoc}
     */
    public function addCustomData($key, $value = NULL)
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
     * @{inerhitDoc}
     */
    public function getCustomData()
    {
        return $this->customData;
    }

    /**
     * @{inerhitDoc}
     */
    public function setExpires(\DateTime $expires)
    {
        // TODO: Auto set UTC Timezone
        $this->expires = $expires;

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @{inerhitDoc}
     */
    public function getPayloadData()
    {
        $this->preparePayload();

        return array(
            'aps' => $this->apsData->getPayloadData()
        ) + $this->customData;
    }

    /**
     * Prepare payload
     */
    protected function preparePayload()
    {
    }
}