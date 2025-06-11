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

namespace Apple\ApnPush\Certificate;

use Apple\ApnPush\Exception\CertificateFileNotFoundException;

class Certificate implements CertificateInterface
{
    private string $path;
    private string $passPhrase;

    public function __construct(string $path, string $passPhrase)
    {
        if (!\file_exists($path) || !\is_file($path)) {
            throw new CertificateFileNotFoundException(\sprintf(
                'The certificate file "%s" was not found.',
                $path
            ));
        }

        $this->path = $path;
        $this->passPhrase = $passPhrase;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getPassPhrase(): string
    {
        return $this->passPhrase;
    }
}
