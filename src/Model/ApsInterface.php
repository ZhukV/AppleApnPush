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
interface ApsInterface
{
    /**
     * Set alert
     *
     * @param Alert $alert
     *
     * @return Aps
     */
    public function withAlert(Alert $alert) : Aps;

    /**
     * Get alert data
     *
     * @return Alert
     */
    public function getAlert() : Alert;

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Aps
     */
    public function withCategory(string $category) : Aps;

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory() : string;

    /**
     * Set sound
     *
     * @param string $sound
     *
     * @return Aps
     */
    public function withSound(string $sound) : Aps;

    /**
     * Get sound
     *
     * @return string
     */
    public function getSound() : string;

    /**
     * Set badge
     *
     * @param int $badge
     *
     * @return Aps
     */
    public function withBadge(int $badge) : Aps;

    /**
     * Get badge
     *
     * @return int|null
     */
    public function getBadge() : ?int;

    /**
     * Set content available option
     *
     * @param bool $contentAvailable
     *
     * @return Aps
     */
    public function withContentAvailable(bool $contentAvailable) : Aps;

    /**
     * Get content available option
     *
     * @return bool
     */
    public function isContentAvailable() : bool;

    /**
     * Set mutable content option
     *
     * @param bool $mutableContent
     *
     * @return Aps
     */
    public function withMutableContent(bool $mutableContent) : Aps;

    /**
     * Get mutable content option
     *
     * @return bool
     */
    public function isMutableContent() : bool;

    /**
     * Set thread id
     *
     * @param string $threadId
     *
     * @return Aps
     */
    public function withThreadId(string $threadId) : Aps;

    /**
     * Get thread id
     *
     * @return string
     */
    public function getThreadId() : string;
}
