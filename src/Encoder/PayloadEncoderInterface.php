<?php

declare(strict_types=1);

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Encoder;

use Apple\ApnPush\Model\Payload;

/**
 * All payload encoder should implement this interface
 */
interface PayloadEncoderInterface
{
    /**
     * Encode push payload for next send to Apple Push Notification Service
     *
     * @param Payload $payload
     *
     * @return string
     */
    public function encode(Payload $payload): string;
}
