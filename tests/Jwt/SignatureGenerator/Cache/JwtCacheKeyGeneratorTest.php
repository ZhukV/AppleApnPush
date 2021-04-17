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

namespace Tests\Apple\ApnPush\Jwt\SignatureGenerator\Cache;

use Apple\ApnPush\Jwt\Jwt;
use Apple\ApnPush\Jwt\SignatureGenerator\Cache\JwtCacheKeyGenerator;
use PHPUnit\Framework\TestCase;

class JwtCacheKeyGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessGenerateWithDefaults(): void
    {
        $jwt = new Jwt('foo', 'bar', __FILE__);
        $generator = new JwtCacheKeyGenerator();

        $cacheKey = $generator->generate($jwt);

        self::assertEquals('apn:jwt:foo', $cacheKey);
    }

    /**
     * @test
     */
    public function shouldSuccessGenerateWithSpecificPrefix(): void
    {
        $jwt = new Jwt('bar', 'foo', __FILE__);
        $generator = new JwtCacheKeyGenerator('some:');

        $cacheKey = $generator->generate($jwt);

        self::assertEquals('some:bar', $cacheKey);
    }
}
