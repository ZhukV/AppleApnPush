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
 * The value object for store the apns-collapse-id
 */
interface CollapseIdInterface
{
    /**
     * Set the value
     *
     * @param string $value
     *
     * @return CollapseId
     *
     * @throws \InvalidArgumentException
     */
    public function withValue(string $value) : CollapseId;

    /**
     * Get the value of collapse id
     *
     * @return string
     */
    public function getValue() : string;
}
