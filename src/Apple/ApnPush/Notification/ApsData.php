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
class ApsData implements ApsDataInterface
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
     * __clone
     */
    public function __clone()
    {
        $this->body = null;
        $this->bodyCustom = array();
        $this->sound = null;
        $this->badge = null;
    }

    /**
     * {@inheritDoc}
     *
     * ATTENTION:
     *  Clear custom body data
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
     * {@inheritDoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function setBodyCustom(array $bodyCustom = array())
    {
        $this->bodyCustom = $bodyCustom;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBodyCustom()
    {
        return $this->bodyCustom;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * {@inheritDoc}
     */
    public function setBadge($badge)
    {
        if (null === $badge) {
            $this->badge = null;
            return $this;
        }

        if ((int) $badge < 0) {
            throw new \InvalidArgumentException(sprintf(
                'Badge key cannot be less than zero (%s)!',
                $badge
            ));
        }

        $this->badge = (int) $badge;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * {@inheritDoc}
     */
    public function getPayloadData()
    {
        $apsData = array(
            'alert' => count($this->bodyCustom) ? $this->bodyCustom : $this->body
        );

        if ($this->sound) {
            $apsData['sound'] = $this->sound;
        }

        if ($this->badge !== null) {
            $apsData['badge'] = $this->badge;
        }

        return $apsData;
    }
}