<?php

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

/**
 * Base certificate
 */
class Certificate implements CertificateInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $passPhrase;

    /**
     * Construct
     *
     * @param string $path
     * @param string $passPhrase
     *
     * @throws CertificateFileNotFoundException
     */
    public function __construct($path, $passPhrase)
    {
        if (!file_exists($path) || !is_file($path)) {
            throw new CertificateFileNotFoundException(sprintf(
                'The certificate file "%s" was not found.',
                $path
            ));
        }

        $this->path = $path;
        $this->passPhrase = $passPhrase;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get pass phrase
     *
     * @return string
     */
    public function getPassPhrase()
    {
        return $this->passPhrase;
    }
}
