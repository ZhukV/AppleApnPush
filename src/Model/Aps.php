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
 * Default APS data
 */
class Aps
{
    /**
     * @var Alert
     */
    private $alert;

    /**
     * @var int|null
     */
    private $badge = null;

    /**
     * @var string|Sound
     */
    private $sound = '';

    /**
     * @var bool
     */
    private $contentAvailable = false;

    /**
     * @var bool
     */
    private $mutableContent = false;

    /**
     * @var string
     */
    private $category = '';

    /**
     * @var string
     */
    private $threadId = '';

    /**
     * Constructor.
     *
     * @param Alert|null $alert
     */
    public function __construct(Alert $alert = null)
    {
        $this->alert = $alert;
    }

    /**
     * Set alert
     *
     * @param Alert $alert
     *
     * @return Aps
     */
    public function withAlert(Alert $alert): Aps
    {
        $cloned = clone $this;

        $cloned->alert = $alert;

        return $cloned;
    }

    /**
     * Get alert data
     *
     * @return Alert
     */
    public function getAlert(): ?Alert
    {
        return $this->alert;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Aps
     */
    public function withCategory(string $category): Aps
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
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Set sound
     *
     * @param string|Sound $sound
     *
     * @return Aps
     */
    public function withSound($sound): Aps
    {
        if (!\is_string($sound) && !$sound instanceof Sound) {
            throw new \InvalidArgumentException(\sprintf(
                'Sound must be a string or %s object, but "%s" given.',
                Sound::class,
                \is_object($sound) ? \get_class($sound) : \gettype($sound)
            ));
        }

        $cloned = clone $this;

        $cloned->sound = $sound;

        return $cloned;
    }

    /**
     * Get sound
     *
     * @return string|Sound
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * Set badge
     *
     * @param int $badge
     *
     * @return Aps
     */
    public function withBadge(int $badge): Aps
    {
        $cloned = clone $this;

        $cloned->badge = (int) $badge;

        return $cloned;
    }

    /**
     * Get badge
     *
     * @return int|null
     */
    public function getBadge(): ?int
    {
        return $this->badge;
    }

    /**
     * Set content available option
     *
     * @param bool $contentAvailable
     *
     * @return Aps
     */
    public function withContentAvailable(bool $contentAvailable): Aps
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
    public function isContentAvailable(): bool
    {
        return $this->contentAvailable;
    }

    /**
     * Set mutable content option
     *
     * @param bool $mutableContent
     *
     * @return Aps
     */
    public function withMutableContent(bool $mutableContent): Aps
    {
        $cloned = clone $this;

        $cloned->mutableContent = $mutableContent;

        return $cloned;
    }

    /**
     * Get mutable content option
     *
     * @return bool
     */
    public function isMutableContent(): bool
    {
        return $this->mutableContent;
    }

    /**
     * Set thread id
     *
     * @param string $threadId
     *
     * @return Aps
     */
    public function withThreadId(string $threadId): Aps
    {
        $cloned = clone $this;

        $cloned->threadId = $threadId;

        return $cloned;
    }

    /**
     * Get thread id
     *
     * @return string
     */
    public function getThreadId(): string
    {
        return $this->threadId;
    }
}
