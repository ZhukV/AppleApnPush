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
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\Converter\JsonConverter;
use Jose\Component\Core\Converter\StandardConverter;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializer;

/**
 * The JWT signature generator worked with "web-token/jwt-*" libraries.
 *
 * Next libraries must be installed:
 *      - web-token/jwt-key-mgmt
 *      - web-token/jwt-core
 *      - web-token/jwt-signature
 */
class WebTokenJwtSignatureGenerator implements SignatureGeneratorInterface
{
    /**
     * @var JsonConverter
     */
    private $jsonConverter;

    /**
     * @var JWSBuilder
     */
    private $jwsBuilder;

    /**
     * @var JWSSerializer
     */
    private $serializer;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->jsonConverter = new StandardConverter();
        $this->jwsBuilder = new JWSBuilder($this->jsonConverter, AlgorithmManager::create([new ES256()]));
        $this->serializer = new CompactSerializer($this->jsonConverter);
    }

    /**
     * {@inheritdoc}
     */
    public function generate(JwtInterface $jwt): string
    {
        $jwk = JWKFactory::createFromKeyFile($jwt->getPath(), '', [
            'kid' => $jwt->getKey()
        ]);

        $claims = [
            'iss' => $jwt->getTeamId(),
            'iat' => time(),
        ];

        $header = [
            'alg' => 'ES256',
            'kid' => $jwk->get('kid'),
        ];

        $payload = $this->jsonConverter->encode($claims);

        $jws = $this->jwsBuilder
            ->create()
            ->withPayload($payload)
            ->addSignature($jwk, $header)
            ->build();

        return $this->serializer->serialize($jws);
    }
}
