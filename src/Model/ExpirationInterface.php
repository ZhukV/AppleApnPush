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
 * Expiration of notification
 */
interface ExpirationInterface
{
    /**
     * Set the availableTo
     *
     * @param \DateTime $availableTo
     *
     * @return Expiration
     */
    public function withStoreTo(\DateTime $availableTo) : Expiration;

    /**
     * Get value
     *
     * @return int
     *
     * @throws \LogicException
     */
    public function getValue() : int;
}
