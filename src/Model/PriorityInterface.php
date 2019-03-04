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
 * Message priority
 */
interface PriorityInterface
{
    /**
     * Set the value
     *
     * @param int $value
     *
     * @return Priority
     *
     * @throws \InvalidArgumentException
     */
    public function withValue(int $value) : Priority;

    /**
     * Get value
     *
     * @return int
     */
    public function getValue() : int;
}
