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
    private $jws = '';

    /**
     * @var \DateInterval
     */
    private $jwsLifetime = null;

    /**
     * @var \DateTime
     */
    private $jwsValidTo = null;

    /**
     * Constructor.
     *
     * @param JwtInterface  $jwt
     * @param \DateInterval $jwsLifetime
     */
    public function __construct(JwtInterface $jwt, \DateInterval $jwsLifetime = null)
    {
        $this->jwt = $jwt;

        $this->jwsLifetime = is_null($jwsLifetime) ? new \DateInterval('P1H') : $jwsLifetime;
        if ($jwsLifetime->invert) {
            throw new \InvalidArgumentException('JWS lifetime must not be inverted');
        }

        $this->jwsLifetime = $jwsLifetime;
    }

    /**
     * Get jws lifetime
     *
     * @return \DateInterval
    */
    public function getJwsLifetime() : \DateInterval
    {
        return $this->jwsLifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Request $request): Request
    {
        $now = new \DateTime();
        if ($this->jws === '' || $this->jwsValidTo < $now) {
            $this->jws = $this->createJwsContent();
            $this->jwsValidTo = ($now)->add($this->jwsLifetime);
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
