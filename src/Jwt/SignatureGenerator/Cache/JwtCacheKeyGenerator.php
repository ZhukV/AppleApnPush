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
 * A simple JWT cache key generator
 */
class JwtCacheKeyGenerator implements JwtCacheKeyGeneratorInterface
{
    /**
     * @var string
     */
    private $cachePrefix;

    /**
     * Constructor.
     *
     * @param string $cachePrefix
     */
    public function __construct(string $cachePrefix = 'apn:jwt:')
    {
        $this->cachePrefix = $cachePrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(JwtInterface $jwt): string
    {
        return \sprintf('%s%s', $this->cachePrefix, $jwt->getTeamId());
    }
}
