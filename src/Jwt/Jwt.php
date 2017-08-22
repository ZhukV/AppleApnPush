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

/**
 * Default Json Web Token for authenticate in provider of apn push service
 */
class Jwt implements JwtInterface
{
    /**
     * @var string
     */
    private $teamId;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $path;

    /**
     * Constructor.
     *
     * @param string $teamId
     * @param string $key
     * @param string $path
     *
     * @throws CertificateFileNotFoundException
     */
    public function __construct(string $teamId, string $key, string $path)
    {
        if (!file_exists($path) || !is_file($path)) {
            throw new CertificateFileNotFoundException(sprintf(
                'The certificate file "%s" was not found.',
                $path
            ));
        }

        $this->teamId = $teamId;
        $this->key = $key;
        $this->path = $path;
    }

    /**
     * Get the identifier of team (Apple Developer)
     *
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->teamId;
    }

    /**
     * Get the key of certificate
     * You can see the key of certificate in Apple Developer Center
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get the path to private certificate
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
