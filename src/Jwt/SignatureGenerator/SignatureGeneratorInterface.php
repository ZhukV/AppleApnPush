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

/**
 * All JWT signature generators should implement this interface.
 */
interface SignatureGeneratorInterface
{
    /**
     * Generate the signature for JSON Web Token
     *
     * @param JwtInterface $jwt
     *
     * @return string
     */
    public function generate(JwtInterface $jwt): string;
}
