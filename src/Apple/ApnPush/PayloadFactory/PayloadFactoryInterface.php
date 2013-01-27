<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\PayloadFactory;

use Apple\ApnPush\Messages\MessageInterface;

/**
 * Interface for cotnrol payload factory
 */
interface PayloadFactoryInterface
{
    /**
     * Create payload
     *
     * @param MessageInterface $message
     */
    public function createPayload(MessageInterface $message);
}