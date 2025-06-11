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

namespace Apple\ApnPush\Jwt;

/**
 * Control Json Web Token for authentication on provider API
 */
interface JwtInterface
{
    /**
     * Get the identifier of team for generate Json Web Token
     *
     * @return string
     */
    public function getTeamId(): string;

    /**
     * Get the key of token
     * You can see the key of certificate in Apple Developer Center
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Get the path to private certificate
     *
     * @return string
     */
    public function getPath(): string;
}
