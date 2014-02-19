<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

/**
 * Default APS data
 */
class ApsData implements ApsDataInterface, \Serializable
{
    /**
     * @var string
     */
    protected $body;

    /**
     * @var array
     */
    protected $bodyCustom = array();

    /**
     * @var string
     */
    protected $sound;

    /**
     * @var integer
     */
    protected $badge;

    /**
     *
     * @var boolean 
     */
    protected $contentAvailable;

    /**
     * __clone
     */
    public function __clone()
    {
        $this->body = null;
        $this->bodyCustom = array();
        $this->sound = null;
        $this->badge = null;
        $this->contentAvailable = null;
    }

    /**
     * Set body
     * Attention: clear custom body data
     *
     * @param string $body
     * @return ApsData
     */
    public function setBody($body)
    {
        // Clear custom body
        $this->bodyCustom = array();

        if (is_object($body)) {
            $body = (string) $body;
        }

        if ($body !== null && !is_string($body) && !is_numeric($body)) {
            throw new \InvalidArgumentException(sprintf(
                'Body message must be string, "%s" given.',
                gettype($body)
            ));
        }

        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set body localize
     *
     * @param string $localizeKey
     * @param array $localizeParams
     * @throws \LogicException          If body message already exists.
     * @return ApsData
     */
    public function setBodyLocalize($localizeKey, array $localizeParams = array())
    {
        if ($localizeKey === null && !count($localizeParams)) {
            $this->bodyCustom = array();

            return $this;
        }

        if ($this->body) {
            throw new \LogicException('Can\'t set localized body, because body message already exists. Please clear body message (->setBody(null)).');
        }

        if (is_object($localizeKey)) {
            $localizeKey = (string) $localizeKey;
        }

        if (!is_string($localizeKey) && !is_numeric($localizeKey)) {
            throw new \InvalidArgumentException(sprintf(
                'Body message must be string, "%s" given',
                gettype($localizeKey)
            ));
        }

        $this->bodyCustom = array(
            'loc-key' => $localizeKey,
            'loc-args' => array_values($localizeParams)
        );

        return $this;
    }

    /**
     * Set custom body
     *
     * @param array $bodyCustom
     * @return ApsData
     */
    public function setBodyCustom(array $bodyCustom = array())
    {
        $this->bodyCustom = $bodyCustom;

        return $this;
    }

    /**
     * Get custom body
     *
     * @return array
     */
    public function getBodyCustom()
    {
        return $this->bodyCustom;
    }

    /**
     * Set sound
     *
     * @param string $sound
     * @throws \InvalidArgumentException
     * @return ApsData
     */
    public function setSound($sound)
    {
        if (is_object($sound)) {
            $sound = (string) $sound;
        }

        if ($sound !== null && !is_string($sound) && !is_numeric($sound)) {
            throw new \InvalidArgumentException(sprintf(
                'Sound must be a string, "%s" given.'
            ));
        }

        $this->sound = $sound;

        return $this;
    }

    /**
     * Get sound
     *
     * @return string
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * Set badge
     *
     * @param int $badge
     * @throw \InvalidArgumentException
     * @return ApsData
     */
    public function setBadge($badge)
    {
        if (null === $badge) {
            $this->badge = null;

            return $this;
        }

        if ((int) $badge < 0) {
            throw new \OutOfRangeException(sprintf(
                'Badge key cannot be less than zero (%s)!',
                $badge
            ));
        }

        $this->badge = (int) $badge;

        return $this;
    }

    /**
     * Get badge
     *
     * @return int
     */
    public function getBadge()
    {
        return $this->badge;
    }

    public function setContentAvailable($contentAvailable)
    {
        $this->contentAvailable = (bool) $contentAvailable;

        return $this;
    }

    public function getContentAvailable()
    {
        return $this->contentAvailable;
    }

    /**
     * Get payload data
     *
     * @return array
     */
    public function getPayloadData()
    {
        $apsData = array(
            'alert' => count($this->bodyCustom) ? $this->bodyCustom : $this->body
        );

        if (null !== $this->sound) {
            $apsData['sound'] = $this->sound;
        }

        if (null !== $this->badge) {
            $apsData['badge'] = $this->badge;
        }

        if (true === $this->contentAvailable) {
            $apsData['content-available'] = 1;
        }
        
        return $apsData;
    }

    /**
     * Serialize APS Data
     *
     * @return string
     */
    public function serialize()
    {
        $data = array(
            'body' => $this->body,
            'body_custom' => $this->bodyCustom,
            'sound' => $this->sound,
            'badge' => $this->badge
        );

        if (true === $this->contentAvailable) {
            $data['content-available'] = 1;
        }

        return serialize($data);
    }

    /**
     * Unserialize APS Data
     *
     * @param string $data
     */
    public function unserialize($data)
    {
        $data = unserialize($data);

        $this
            ->setBody($data['body'])
            ->setBodyCustom($data['body_custom'])
            ->setSound($data['sound'])
            ->setBadge($data['badge']);
        
        if (isset($data['content-available'])) {
            $this->setContentAvailable($data['content-available']);
        }
    }
}
