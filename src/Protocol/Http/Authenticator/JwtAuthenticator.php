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
use Apple\ApnPush\Jwt\SignatureGenerator\SignatureGeneratorFactory;
use Apple\ApnPush\Jwt\SignatureGenerator\SignatureGeneratorInterface;
use Apple\ApnPush\Protocol\Http\Request;

/**
 * Authenticate request via Json Web Token
 */
class JwtAuthenticator implements AuthenticatorInterface
{
    /**
     * @var JwtInterface
     */
    private $jwt;

    /**
     * @var SignatureGeneratorInterface
     */
    private $signatureGenerator;

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
     * @param JwtInterface                     $jwt
     * @param \DateInterval|null               $jwsLifetime
     * @param SignatureGeneratorInterface|null $signatureGenerator
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function __construct(JwtInterface $jwt, \DateInterval $jwsLifetime = null, SignatureGeneratorInterface $signatureGenerator = null)
    {
        $this->jwt = $jwt;

        if ($jwsLifetime && $jwsLifetime->invert) {
            throw new \InvalidArgumentException('JWS lifetime must not be inverted');
        }

        $this->jwsLifetime = $jwsLifetime;

        $this->signatureGenerator = $signatureGenerator ?: SignatureGeneratorFactory::resolve();
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Request $request): Request
    {
        $now = new \DateTimeImmutable();

        if (!$this->jws || $this->jwsValidTo < $now) {
            $this->jws = $this->signatureGenerator->generate($this->jwt);
            $this->jwsValidTo = $this->jwsLifetime ? ($now)->add($this->jwsLifetime) : $this->jwsValidTo;
        }

        return $request->withHeader('authorization', sprintf('bearer %s', $this->jws));
    }
}
