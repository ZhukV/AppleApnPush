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
 * Value object for alert object
 */
class Alert
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * @var Localized
     */
    private $titleLocalized;

    /**
     * @var string
     */
    private $body = '';

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
     */
    public function __construct(string $body = '', string $title = '')
    {
        $this->body = $body;
        $this->title = $title;
        $this->titleLocalized = new Localized('');
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
    public function withTitle(string $title) : Alert
    {
        $cloned = clone $this;

        $this->titleLocalized = new Localized('');
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
    public function withLocalizedTitle(Localized $localized) : Alert
    {
        $cloned = clone $this;

        $cloned->title = '';
        $cloned->titleLocalized = $localized;

        return $cloned;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Get localized title
     *
     * @return Localized
     */
    public function getTitleLocalized() : Localized
    {
        return $this->titleLocalized;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Alert
     */
    public function withBody(string $body) : Alert
    {
        $cloned = clone $this;

        $cloned->bodyLocalized = new Localized('');
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
    public function withLocalizedBody(Localized $localized) : Alert
    {
        $cloned = clone $this;

        $cloned->body = '';
        $cloned->bodyLocalized = $localized;

        return $cloned;
    }

    /**
     * Get localized body
     *
     * @return Localized
     */
    public function getBodyLocalized() : Localized
    {
        return $this->bodyLocalized;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody() : string
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
    public function withLocalizedAction(Localized $localized) : Alert
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
    public function getLocalizedAction() : Localized
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
    public function withLaunchImage(string $launchImage) : Alert
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
    public function getLaunchImage() : string
    {
        return $this->launchImage;
    }
}
