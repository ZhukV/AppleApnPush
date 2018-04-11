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

use Jose\Component\Core\JWK;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\JWS;

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

        self::addResolver([__CLASS__, 'tryResolveByWebTokenJwtSystem']);
    }

    /**
     * Try the resolve WebTokenJwtSignatureGenerator
     *
     * @return WebTokenJwtSignatureGenerator|null
     */
    private static function tryResolveByWebTokenJwtSystem(): ?WebTokenJwtSignatureGenerator
    {
        $requiredClasses = [
            JWS::class,
            JWK::class,
            JWKFactory::class,
        ];

        foreach ($requiredClasses as $requiredClass) {
            if (!class_exists($requiredClass)) {
                return null;
            }
        }

        return new WebTokenJwtSignatureGenerator();
    }
}
