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

namespace Apple\ApnPush\Protocol\Http\Authenticator;

use Apple\ApnPush\Jwt\JwtInterface;
use Apple\ApnPush\Protocol\Http\Request;
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

/**
 * Authenticate request via Json Web Token
 */
class JwtAuthenticator implements AuthenticatorInterface
{
    private const ALGORITHM = 'ES256';

    /**
     * @var JwtInterface
     */
    private $jwt;

    /**
     * @var string
     */
    private $jws;

    /**
     * @var \DateInterval
     */
    private $jwsLifetime;

    /**
     * @var \DateTime
     */
    private $jwsValidTo;

    /**
     * Constructor.
     *
     * @param JwtInterface  $jwt
     * @param \DateInterval $jwsLifetime
     * @throws \InvalidArgumentException
     */
    public function __construct(JwtInterface $jwt, \DateInterval $jwsLifetime = null)
    {
        $this->jwt = $jwt;
        if ($jwsLifetime && $jwsLifetime->invert) {
            throw new \InvalidArgumentException('JWS lifetime must not be inverted');
        }

        $this->jwsLifetime = $jwsLifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Request $request): Request
    {
        $now = new \DateTime();
        if (!$this->jws || !$this->jwsLifetime || $this->jwsValidTo < $now) {
            $this->jws = $this->createJwsContent();
            $this->jwsValidTo = $this->jwsLifetime ? ($now)->add($this->jwsLifetime) : $this->jwsValidTo;
        }

        $request = $request->withHeader('authorization', sprintf('bearer %s', $this->jws));

        return $request;
    }

    /**
     * Create the content of JWS by Json Web Token
     *
     * @return string
     */
    private function createJwsContent(): string
    {
        $jwk = JWKFactory::createFromKeyFile($this->jwt->getPath(), '', [
            'kid' => $this->jwt->getKey(),
            'alg' => self::ALGORITHM,
            'use' => 'sig',
        ]);

        $payload = [
            'iss' => $this->jwt->getTeamId(),
            'iat' => time(),
        ];

        $header = [
            'alg' => self::ALGORITHM,
            'kid' => $jwk->get('kid'),
        ];

        return JWSFactory::createJWSToCompactJSON(
            $payload,
            $jwk,
            $header
        );
    }
}
