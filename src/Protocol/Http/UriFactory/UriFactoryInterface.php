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

namespace Apple\ApnPush\Protocol\Http\UriFactory;

use Apple\ApnPush\Model\DeviceToken;

/**
 * All URI factories for create URI for send push notification via HTTP should implement this interface
 */
interface UriFactoryInterface
{
    /**
     * Create URI for send request
     *
     * @param DeviceToken $deviceToken
     * @param bool        $sandbox
     *
     * @return string
     */
    public function create(DeviceToken $deviceToken, bool $sandbox): string;
}
