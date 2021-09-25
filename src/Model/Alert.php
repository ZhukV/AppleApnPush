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
 * Value object for alert object
 */
class Alert
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var Localized
     */
    private $titleLocalized;

    /**
     * @var string
     */
    private $subtitle;

    /**
     * @var Localized
     */
    private $subtitleLocalized;

    /**
     * @var string
     */
    private $body;

    /**
     * @var Localized
     */
    private $bodyLocalized;

    /**
     * @var Localized
     */
    private $actionLocalized;

    /**
     * @var string
     */
    private $launchImage = '';

    /**
     * Constructor.
     *
     * @param string $body
     * @param string $title
     * @param string $subtitle
     */
    public function __construct(string $body = '', string $title = '', string $subtitle = '')
    {
        $this->body = $body;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->titleLocalized = new Localized('');
        $this->subtitleLocalized = new Localized('');
        $this->bodyLocalized = new Localized('');
        $this->actionLocalized = new Localized('');
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Alert
     */
    public function withTitle(string $title): Alert
    {
        $cloned = clone $this;

        $cloned->title = $title;

        return $cloned;
    }

    /**
     * With localized title
     *
     * @param Localized $localized
     *
     * @return Alert
     */
    public function withLocalizedTitle(Localized $localized): Alert
    {
        $cloned = clone $this;

        $cloned->titleLocalized = $localized;

        return $cloned;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get localized title
     *
     * @return Localized
     */
    public function getTitleLocalized(): Localized
    {
        return $this->titleLocalized;
    }


    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return Alert
     */
    public function withSubtitle(string $subtitle): Alert
    {
        $cloned = clone $this;

        $cloned->subtitle = $subtitle;

        return $cloned;
    }

    /**
     * With localized subtitle
     *
     * @param Localized $localized
     *
     * @return Alert
     */
    public function withLocalizedSubtitle(Localized $localized): Alert
    {
        $cloned = clone $this;

        $cloned->subtitleLocalized = $localized;

        return $cloned;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * Get localized subtitle
     *
     * @return Localized
     */
    public function getSubtitleLocalized(): Localized
    {
        return $this->subtitleLocalized;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Alert
     */
    public function withBody(string $body): Alert
    {
        $cloned = clone $this;

        $cloned->body = $body;

        return $cloned;
    }

    /**
     * Set localized body
     *
     * @param Localized $localized
     *
     * @return Alert
     */
    public function withBodyLocalized(Localized $localized): Alert
    {
        $cloned = clone $this;

        $cloned->bodyLocalized = $localized;

        return $cloned;
    }

    /**
     * Get localized body
     *
     * @return Localized
     */
    public function getBodyLocalized(): Localized
    {
        return $this->bodyLocalized;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * With localized action
     *
     * @param Localized $localized
     *
     * @return Alert
     */
    public function withActionLocalized(Localized $localized): Alert
    {
        $cloned = clone $this;

        $cloned->actionLocalized = $localized;

        return $cloned;
    }

    /**
     * Get localized action
     *
     * @return Localized
     */
    public function getActionLocalized(): Localized
    {
        return $this->actionLocalized;
    }

    /**
     * Add launch image
     *
     * @param string $launchImage
     *
     * @return Alert
     */
    public function withLaunchImage(string $launchImage): Alert
    {
        $cloned = clone $this;

        $cloned->launchImage = $launchImage;

        return $cloned;
    }

    /**
     * Get launch image
     *
     * @return string
     */
    public function getLaunchImage(): string
    {
        return $this->launchImage;
    }
}
