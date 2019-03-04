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
interface AlertInterface
{
    /**
     * Set title
     *
     * @param string $title
     *
     * @return Alert
     */
    public function withTitle(string $title) : Alert;

    /**
     * With localized title
     *
     * @param Localized $localized
     *
     * @return Alert
     */
    public function withLocalizedTitle(Localized $localized) : Alert;

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() : string;

    /**
     * Get localized title
     *
     * @return Localized
     */
    public function getTitleLocalized() : Localized;

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Alert
     */
    public function withBody(string $body) : Alert;

    /**
     * Set localized body
     *
     * @param Localized $localized
     *
     * @return Alert
     */
    public function withBodyLocalized(Localized $localized) : Alert;

    /**
     * Get localized body
     *
     * @return Localized
     */
    public function getBodyLocalized() : Localized;

    /**
     * Get body
     *
     * @return string
     */
    public function getBody() : string;

    /**
     * With localized action
     *
     * @param Localized $localized
     *
     * @return Alert
     */
    public function withActionLocalized(Localized $localized) : Alert;

    /**
     * Get localized action
     *
     * @return Localized
     */
    public function getActionLocalized() : Localized;

    /**
     * Add launch image
     *
     * @param string $launchImage
     *
     * @return Alert
     */
    public function withLaunchImage(string $launchImage) : Alert;

    /**
     * Get launch image
     *
     * @return string
     */
    public function getLaunchImage() : string;
}
