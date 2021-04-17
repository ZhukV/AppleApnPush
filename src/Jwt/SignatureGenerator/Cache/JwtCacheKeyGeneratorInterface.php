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

namespace Apple\ApnPush\Jwt\SignatureGenerator\Cache;

use Apple\ApnPush\Jwt\JwtInterface;

/**
 * All JWT cache key generators should implement this interface.
 */
interface JwtCacheKeyGeneratorInterface
{
    /**
     * Generate a cache key for store JWT token
     *
     * @param JwtInterface $jwt
     *
     * @return string
     */
    public function generate(JwtInterface $jwt): string;
}
