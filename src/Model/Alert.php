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

class Alert
{
    private string $title;
    private Localized $titleLocalized;
    private string $subtitle;
    private Localized $subtitleLocalized;
    private string $body;
    private Localized $bodyLocalized;
    private Localized $actionLocalized;
    private string $launchImage = '';

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

    public function withTitle(string $title): self
    {
        $cloned = clone $this;

        $cloned->title = $title;

        return $cloned;
    }

    public function withLocalizedTitle(Localized $localized): self
    {
        $cloned = clone $this;

        $cloned->titleLocalized = $localized;

        return $cloned;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTitleLocalized(): Localized
    {
        return $this->titleLocalized;
    }

    public function withSubtitle(string $subtitle): self
    {
        $cloned = clone $this;

        $cloned->subtitle = $subtitle;

        return $cloned;
    }

    public function withLocalizedSubtitle(Localized $localized): self
    {
        $cloned = clone $this;

        $cloned->subtitleLocalized = $localized;

        return $cloned;
    }

    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    public function getSubtitleLocalized(): Localized
    {
        return $this->subtitleLocalized;
    }

    public function withBody(string $body): Alert
    {
        $cloned = clone $this;

        $cloned->body = $body;

        return $cloned;
    }

    public function withBodyLocalized(Localized $localized): self
    {
        $cloned = clone $this;

        $cloned->bodyLocalized = $localized;

        return $cloned;
    }

    public function getBodyLocalized(): Localized
    {
        return $this->bodyLocalized;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function withActionLocalized(Localized $localized): self
    {
        $cloned = clone $this;

        $cloned->actionLocalized = $localized;

        return $cloned;
    }

    public function getActionLocalized(): Localized
    {
        return $this->actionLocalized;
    }

    public function withLaunchImage(string $launchImage): self
    {
        $cloned = clone $this;

        $cloned->launchImage = $launchImage;

        return $cloned;
    }

    public function getLaunchImage(): string
    {
        return $this->launchImage;
    }
}
