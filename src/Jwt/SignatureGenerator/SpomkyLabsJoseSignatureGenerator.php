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
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

/**
 * The signature generator with use "spomky-labs/jose" library.
 */
class SpomkyLabsJoseSignatureGenerator implements SignatureGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(JwtInterface $jwt): string
    {
        $jwk = JWKFactory::createFromKeyFile($jwt->getPath(), '', [
            'kid' => $jwt->getKey(),
            'alg' => 'ES256',
            'use' => 'sig',
        ]);

        $payload = [
            'iss' => $jwt->getTeamId(),
            'iat' => \time(),
        ];

        $header = [
            'alg' => 'ES256',
            'kid' => $jwk->get('kid'),
        ];

        return JWSFactory::createJWSToCompactJSON(
            $payload,
            $jwk,
            $header
        );
    }
}
