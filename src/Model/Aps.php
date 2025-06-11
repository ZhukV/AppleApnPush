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

class Aps
{
    private ?Alert $alert;
    private ?int $badge = null;
    private bool $contentAvailable = false;
    private bool $mutableContent = false;
    private string $category = '';
    private string $threadId = '';
    private array $customData;
    private ?array $urlArgs = null;

    /**
     * @var string|Sound
     */
    private $sound = '';

    public function __construct(?Alert $alert = null, array $customData = [])
    {
        $this->alert = $alert;
        $this->customData = $customData;
    }

    public function withAlert(Alert $alert): self
    {
        $cloned = clone $this;

        $cloned->alert = $alert;

        return $cloned;
    }

    public function getAlert(): ?Alert
    {
        return $this->alert;
    }

    public function withCategory(string $category): self
    {
        $cloned = clone $this;

        $cloned->category = $category;

        return $cloned;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Set sound for APS
     *
     * @param string|Sound|mixed $sound
     *
     * @return self
     */
    public function withSound($sound): self
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
     * Get sound for APS
     *
     * @return Sound|string
     */
    public function getSound()
    {
        return $this->sound;
    }

    public function withBadge(int $badge): self
    {
        $cloned = clone $this;

        $cloned->badge = $badge;

        return $cloned;
    }

    public function getBadge(): ?int
    {
        return $this->badge;
    }

    public function withContentAvailable(bool $contentAvailable): self
    {
        $cloned = clone $this;

        $cloned->contentAvailable = $contentAvailable;

        return $cloned;
    }

    public function isContentAvailable(): bool
    {
        return $this->contentAvailable;
    }

    public function withMutableContent(bool $mutableContent): self
    {
        $cloned = clone $this;

        $cloned->mutableContent = $mutableContent;

        return $cloned;
    }

    public function isMutableContent(): bool
    {
        return $this->mutableContent;
    }

    public function withThreadId(string $threadId): self
    {
        $cloned = clone $this;

        $cloned->threadId = $threadId;

        return $cloned;
    }

    public function getThreadId(): string
    {
        return $this->threadId;
    }

    public function withUrlArgs(array $urlArgs): self
    {
        $cloned = clone $this;

        $cloned->urlArgs = $urlArgs;

        return $cloned;
    }

    public function getUrlArgs(): ?array
    {
        return $this->urlArgs;
    }

    public function getCustomData(): array
    {
        return $this->customData;
    }

    /**
     * Set or replace custom data by name
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return self
     */
    public function withCustomData(string $name, $value): self
    {
        if ($value && !is_array($value) && !is_scalar($value) && !$value instanceof \JsonSerializable) {
            throw new \InvalidArgumentException(sprintf(
                'The custom data value should be a scalar or \JsonSerializable instance, but "%s" given.',
                \is_object($value) ? \get_class($value) : \gettype($value)
            ));
        }

        $cloned = clone $this;

        $cloned->customData[$name] = $value;

        return $cloned;
    }
}
