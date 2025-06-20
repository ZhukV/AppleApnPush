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

namespace Apple\ApnPush\Jwt\SignatureGenerator;

use Apple\ApnPush\Jwt\JwtInterface;
use Apple\ApnPush\Jwt\SignatureGenerator\Cache\JwtCacheKeyGenerator;
use Apple\ApnPush\Jwt\SignatureGenerator\Cache\JwtCacheKeyGeneratorInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * The decorator for store JWT token into cache.
 */
class CacheJwtSignatureGenerator implements SignatureGeneratorInterface
{
    /**
     * @var SignatureGeneratorInterface
     */
    private $generator;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var JwtCacheKeyGeneratorInterface
     */
    private $cacheKeyGenerator;

    /**
     * @var \DateInterval
     */
    private $ttl;

    /**
     * Constructor.
     *
     * @param SignatureGeneratorInterface        $generator
     * @param CacheInterface                     $cache
     * @param JwtCacheKeyGeneratorInterface|null $cacheKeyGenerator
     * @param \DateInterval|null                 $ttl
     */
    public function __construct(SignatureGeneratorInterface $generator, CacheInterface $cache, ?JwtCacheKeyGeneratorInterface $cacheKeyGenerator = null, ?\DateInterval $ttl = null)
    {
        $this->generator = $generator;
        $this->cache = $cache;
        $this->cacheKeyGenerator = $cacheKeyGenerator ?: new JwtCacheKeyGenerator();
        $this->ttl = $ttl ?: new \DateInterval('PT30M');
    }

    /**
     * {@inheritdoc}
     */
    public function generate(JwtInterface $jwt): string
    {
        $cacheKey = $this->cacheKeyGenerator->generate($jwt);

        if ($jwtToken = $this->cache->get($cacheKey)) {
            return $jwtToken;
        }

        $jwtToken = $this->generator->generate($jwt);

        $this->cache->set($cacheKey, $jwtToken, $this->ttl);

        return $jwtToken;
    }
}
