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
     * @var integer
     */
    private $jwsLifetime = 3600;

    /**
     * @var integer
     */
    private $jwsCreatedAt = 0;

    /**
     * Constructor.
     *
     * @param JwtInterface $jwt
     */
    public function __construct(JwtInterface $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Get jws lifetime
     *
     * @return integer
    */
    public function getJwsLifetime() : int
    {
        return $this->jwsLifetime;
    }

    /**
     * Set jws lifetime
     *
     * @param integer $jwsLifetime
     */
    public function setJwsLifetime(int  $jwsLifetime)
    {
        $this->jwsLifetime = abs($jwsLifetime);//ignore sign
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Request $request): Request
    {
        if (empty($this->jws) || $this->jwsCreatedAt < time() - $this->jwsLifetime) {
            $this->jws = $this->createJwsContent();
            $this->jwsCreatedAt = time();
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
