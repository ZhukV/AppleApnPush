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

use Apple\ApnPush\Sender\SenderInterface;

/**
 * All builders for build senders should implement this interface
 */
interface BuilderInterface
{
    /**
     * Build sender for send notification to device via Apn Push service
     *
     * @return SenderInterface
     */
    public function build(): SenderInterface;
}
