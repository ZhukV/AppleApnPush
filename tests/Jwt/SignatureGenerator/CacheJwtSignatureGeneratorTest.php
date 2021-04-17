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

namespace Tests\Apple\ApnPush\Jwt\SignatureGenerator;

use Apple\ApnPush\Jwt\Jwt;
use Apple\ApnPush\Jwt\SignatureGenerator\Cache\JwtCacheKeyGeneratorInterface;
use Apple\ApnPush\Jwt\SignatureGenerator\CacheJwtSignatureGenerator;
use Apple\ApnPush\Jwt\SignatureGenerator\SignatureGeneratorInterface;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class CacheJwtSignatureGeneratorTest extends TestCase
{
    /**
     * @var SignatureGeneratorInterface
     */
    private $originGenerator;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var JwtCacheKeyGeneratorInterface
     */
    private $keyGenerator;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->originGenerator = $this->createMock(SignatureGeneratorInterface::class);
        $this->cache = $this->createMock(CacheInterface::class);
        $this->keyGenerator = $this->createMock(JwtCacheKeyGeneratorInterface::class);
    }

    /**
     * @test
     */
    public function shouldSuccessGetIfExist(): void
    {
        $jwt = new Jwt('foo', 'bar', __FILE__);

        $this->keyGenerator->expects(self::once())
            ->method('generate')
            ->with($jwt)
            ->willReturn('foo:bar');

        $this->cache->expects(self::once())
            ->method('get')
            ->with('foo:bar')
            ->willReturn('cached token');

        $this->originGenerator->expects(self::never())
            ->method('generate');

        $generator = new CacheJwtSignatureGenerator($this->originGenerator, $this->cache, $this->keyGenerator);
        $token = $generator->generate($jwt);

        self::assertEquals('cached token', $token);
    }

    /**
     * @test
     */
    public function shouldSuccessGetIfNotExist(): void
    {
        $jwt = new Jwt('foo', 'bar', __FILE__);

        $this->keyGenerator->expects(self::once())
            ->method('generate')
            ->with($jwt)
            ->willReturn('bar:foo');

        $this->cache->expects(self::once())
            ->method('get')
            ->with('bar:foo')
            ->willReturn(null);

        $this->originGenerator->expects(self::once())
            ->method('generate')
            ->with($jwt)
            ->willReturn('generated token');

        $this->cache->expects(self::once())
            ->method('set')
            ->with('bar:foo', 'generated token', new \DateInterval('PT30M'));

        $generator = new CacheJwtSignatureGenerator($this->originGenerator, $this->cache, $this->keyGenerator);
        $token = $generator->generate($jwt);

        self::assertEquals('generated token', $token);
    }

    /**
     * @test
     */
    public function shouldSuccessStoreWithSpecificTtl(): void
    {
        $jwt = new Jwt('foo', 'bar', __FILE__);

        $this->keyGenerator->expects(self::once())
            ->method('generate')
            ->with($jwt)
            ->willReturn('bar:foo');

        $this->cache->expects(self::once())
            ->method('get')
            ->with('bar:foo')
            ->willReturn(null);

        $this->originGenerator->expects(self::once())
            ->method('generate')
            ->with($jwt)
            ->willReturn('generated token');

        $this->cache->expects(self::once())
            ->method('set')
            ->with('bar:foo', 'generated token', new \DateInterval('PT20M'));

        $generator = new CacheJwtSignatureGenerator($this->originGenerator, $this->cache, $this->keyGenerator, new \DateInterval('PT20M'));
        $token = $generator->generate($jwt);

        self::assertEquals('generated token', $token);
    }
}
