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

namespace Apple\ApnPush\Protocol\Http\UriFactory;

use Apple\ApnPush\Model\DeviceToken;

/**
 * Default URI factory
 */
class UriFactory implements UriFactoryInterface
{
    /**
     * Create URI for device
     *
     * @param DeviceToken $deviceToken
     * @param bool        $sandbox
     *
     * @return string
     */
    public function create(DeviceToken $deviceToken, bool $sandbox): string
    {
        $uri = 'https://api.push.apple.com/3/device/%s';

        if ($sandbox) {
            $uri = 'https://api.development.push.apple.com/3/device/%s';
        }

        return sprintf($uri, $deviceToken);
    }
}
