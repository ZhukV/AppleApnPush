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

use Apple\ApnPush\Exception\CertificateFileNotFoundException;

class Jwt implements JwtInterface
{
    private string $teamId;
    private string $key;
    private string $path;

    public function __construct(string $teamId, string $key, string $path)
    {
        if (!\file_exists($path) || !\is_file($path)) {
            throw new CertificateFileNotFoundException(\sprintf(
                'The certificate file "%s" was not found.',
                $path
            ));
        }

        $this->teamId = $teamId;
        $this->key = $key;
        $this->path = $path;
    }

    public function getTeamId(): string
    {
        return $this->teamId;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
