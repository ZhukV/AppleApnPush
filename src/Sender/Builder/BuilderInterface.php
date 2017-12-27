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

namespace Apple\ApnPush\Sender\Builder;

use Apple\ApnPush\Protocol\ProtocolInterface;
use Apple\ApnPush\Sender\SenderInterface;

/**
 * All builders for build senders should implement this interface
 */
interface BuilderInterface
{
    /**
     * Build the protocol for send the notification to devices
     *
     * @param int $maxRequests
     * @param int $timeout
     *
     * @return ProtocolInterface
     */
    public function buildProtocol($maxRequests = 20, $timeout = 5000): ProtocolInterface;

    /**
     * Build sender for send notification to device via Apn Push service
     *
     * @return SenderInterface
     */
    public function build(): SenderInterface;
}
