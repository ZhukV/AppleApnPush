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
     * Constructor.
     *
     * @param JwtInterface $jwt
     */
    public function __construct(JwtInterface $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Request $request): Request
    {
        $jws = $this->createJwsContent();

        $request = $request->withHeader('authorization', sprintf('bearer %s', $jws));

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
