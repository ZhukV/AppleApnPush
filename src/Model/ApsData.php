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
 * Default APS data
 */
class ApsData implements \Serializable
{
    /**
     * @var string
     */
    protected $body = '';

    /**
     * @var array
     */
    protected $bodyCustom = [];

    /**
     * @var string
     */
    protected $sound = '';

    /**
     * @var string
     */
    protected $category = '';

    /**
     * @var int
     */
    protected $badge = 0;

    /**
     * @var bool
     */
    protected $contentAvailable = false;

    /**
     * Set body
     *
     * @param string $body
     *
     * @return ApsData
     */
    public function withBody($body)
    {
        $cloned = clone $this;

        $cloned->bodyCustom = [];
        $cloned->body = $body;

        return $cloned;
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
     * Set category
     *
     * @param string $category
     *
     * @return ApsData
     */
    public function withCategory($category)
    {
        $cloned = clone $this;

        $cloned->category = $category;

        return $cloned;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set body localize
     *
     * @param string $localizeKey
     * @param array  $localizeParams
     *
     * @return ApsData
     */
    public function withBodyLocalize($localizeKey, array $localizeParams = [])
    {
        $cloned = clone $this;

        $cloned->bodyCustom = [
            'loc-key' => $localizeKey,
            'loc-args' => array_values($localizeParams),
        ];

        return $cloned;
    }

    /**
     * Set custom body
     *
     * @param array $bodyCustom
     *
     * @return ApsData
     */
    public function withBodyCustom(array $bodyCustom = [])
    {
        $cloned = clone $this;
        $cloned->bodyCustom = $bodyCustom;

        return $cloned;
    }

    /**
     * Get custom body
     *
     * @return array
     */
    public function getBodyCustom() : array
    {
        return $this->bodyCustom;
    }

    /**
     * Set sound
     *
     * @param string $sound
     *
     * @return ApsData
     */
    public function withSound(string $sound) : ApsData
    {
        $cloned = clone $this;

        $cloned->sound = $sound;

        return $cloned;
    }

    /**
     * Get sound
     *
     * @return string
     */
    public function getSound() : string
    {
        return $this->sound;
    }

    /**
     * Set badge
     *
     * @param int $badge
     *
     * @return ApsData
     */
    public function withBadge(int $badge) : ApsData
    {
        $cloned = clone $this;

        $cloned->badge = (int) $badge;

        return $cloned;
    }

    /**
     * Get badge
     *
     * @return int
     */
    public function getBadge() : int
    {
        return $this->badge;
    }

    /**
     * Set content available option
     *
     * @param bool $contentAvailable
     *
     * @return ApsData
     */
    public function withContentAvailable(bool $contentAvailable) : ApsData
    {
        $cloned = clone $this;

        $cloned->contentAvailable = $contentAvailable;

        return $cloned;
    }

    /**
     * Get content available option
     *
     * @return bool
     */
    public function isContentAvailable() : bool
    {
        return $this->contentAvailable;
    }

    /**
     * Serialize APS Data
     *
     * @return string
     */
    public function serialize()
    {
        $data = [
            'body' => $this->body,
            'body_custom' => $this->bodyCustom,
            'sound' => $this->sound,
            'badge' => $this->badge,
            'category' => $this->category,
        ];

        if (true === $this->contentAvailable) {
            $data['content-available'] = true;
        }

        return json_encode($data);
    }

    /**
     * Unserialize APS Data
     *
     * @param string $data
     */
    public function unserialize($data)
    {
        $data = json_decode($data, true);

        $this->body = $data['body'];
        $this->bodyCustom = $data['body_custom'];
        $this->sound = $data['sound'];
        $this->badge = $data['badge'];
        $this->category = $data['category'];

        if (array_key_exists('content-available', $data)) {
            $this->contentAvailable = true;
        }
    }
}
