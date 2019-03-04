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
 * Payload model
 */
interface PayloadInterface
{
    /**
     * Set aps
     *
     * @param Aps $aps
     *
     * @return Payload
     */
    public function withAps(Aps $aps) : Payload;

    /**
     * Get APS data
     *
     * @return Aps
     */
    public function getAps() : Aps;

    /**
     * Add or replace custom data
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return Payload
     *
     * @throws \InvalidArgumentException
     */
    public function withCustomData(string $name, $value) : Payload;

    /**
     * Get custom data
     *
     * @return array
     */
    public function getCustomData() : array;
}
