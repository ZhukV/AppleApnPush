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
        $this->body = NULL;
        $this->bodyCustom = array();
        $this->sound = NULL;
        $this->badge = NULL;
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

        if ($body !== NULL && !is_string($body) && !is_numeric($body)) {
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
    public function setBodyLocalize($localizeKey, array $localizeParams)
    {
        if ($localizeKey === NULL && !count($localizeParams)) {
            $this->bodyCustom = array();
            return $this;
        }

        if ($this->body) {
            throw new \LogicException('Can\'t set localized body, because body message already exists. Please clear body message (->setBody(NULL)).');
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
    public function setBodyCustom(array $bodyCustom)
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

        if ($sound !== NULL && !is_string($sound) && !is_numeric($sound)) {
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
        if ($badge === NULL) {
            $this->badge = NULL;
            return $this;
        }

        if (0 >= (int) $badge) {
            throw new \InvalidArgumentException(sprintf(
                'Badge key must be large than zero (%s)!',
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

        if ($this->badge) {
            $apsData['badge'] = $this->badge;
        }

        return $apsData;
    }
}