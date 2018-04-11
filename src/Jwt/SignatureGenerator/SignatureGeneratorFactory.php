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

namespace Apple\ApnPush\Jwt\SignatureGenerator;

use Jose\Component\Core\JWK as WebTokenComponentJwk;
use Jose\Component\KeyManagement\JWKFactory as WebTokenComponentJWKFactory;
use Jose\Component\Signature\JWS as WebTokenComponentJws;
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

/**
 * The factory for try to resolve the JWT signature generator.
 */
class SignatureGeneratorFactory
{
    /**
     * @var array|callable[]
     */
    private static $resolvers = [];

    /**
     * Add resolver for try to resolve the signature generator
     *
     * @param callable $resolver
     */
    public static function addResolver(callable $resolver): void
    {
        array_unshift(self::$resolvers, $resolver);
    }

    /**
     * Try to resolve the signature generator
     *
     * @return SignatureGeneratorInterface
     *
     * @throws \LogicException
     */
    public static function resolve(): SignatureGeneratorInterface
    {
        self::addDefaultResolvers();

        foreach (self::$resolvers as $resolver) {
            if ($generator = $resolver()) {
                return $generator;
            }
        }

        throw new \LogicException('Cannot resolve available JWT Signature Generator.');
    }

    /**
     * Add default signature generator resolvers
     */
    private static function addDefaultResolvers(): void
    {
        static $added = false;

        if ($added) {
            return;
        }

        $added = true;

        self::addResolver([__CLASS__, 'tryResolveByWebTokenJwtSystem']);
        self::addResolver([__CLASS__, 'tryResolveBySpomkyLabsJoseSystem']);
    }

    /**
     * Try the resolve WebTokenJwtSignatureGenerator
     *
     * @return WebTokenJwtSignatureGenerator|null
     */
    private static function tryResolveByWebTokenJwtSystem(): ?WebTokenJwtSignatureGenerator
    {
        $requiredClasses = [
            WebTokenComponentJws::class,
            WebTokenComponentJwk::class,
            WebTokenComponentJWKFactory::class,
        ];

        foreach ($requiredClasses as $requiredClass) {
            if (!class_exists($requiredClass)) {
                return null;
            }
        }

        return new WebTokenJwtSignatureGenerator();
    }

    /**
     * Try to resolve SpomkyLabsJoseSignatureGenerator
     *
     * @return SpomkyLabsJoseSignatureGenerator|null
     */
    private static function tryResolveBySpomkyLabsJoseSystem(): ?SpomkyLabsJoseSignatureGenerator
    {
        $requiredClasses = [
            JWKFactory::class,
            JWSFactory::class,
        ];

        foreach ($requiredClasses as $requiredClass) {
            if (!class_exists($requiredClass)) {
                return null;
            }
        }

        return new SpomkyLabsJoseSignatureGenerator();
    }
}
