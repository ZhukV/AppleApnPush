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
 * UUID identifier for APN
 */
interface ApnIdInterface
{
    /**
     * Set the value
     *
     * @param string $value
     *
     * @return ApnId
     *
     * @throws \InvalidArgumentException
     */
    public function withValue(string $value) : ApnId;

    /**
     * Get value
     *
     * @return string
     */
    public function getValue() : string;
}
