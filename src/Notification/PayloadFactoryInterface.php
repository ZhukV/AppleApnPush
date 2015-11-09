<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

/**
 * Interface for control payload factory
 */
interface PayloadFactoryInterface
{
    /**
     * Create payload for message
     *
     * @param MessageInterface $message
     */
    public function createPayload(MessageInterface $message);
}
