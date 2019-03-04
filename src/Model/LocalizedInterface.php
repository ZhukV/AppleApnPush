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
 * Value object for store localized information
 */
interface LocalizedInterface
{
    /**
     * Set the key
     *
     * @param string $key
     *
     * @return Localized
     */
    public function withKey(string $key) : Localized;

    /**
     * Get localized key
     *
     * @return string
     */
    public function getKey() : string;

    /**
     * Set the args
     *
     * @param array $args
     *
     * @return Localized
     */
    public function withArgs(array $args) : Localized;

    /**
     * Get localized args
     *
     * @return array
     */
    public function getArgs() : array;
}
