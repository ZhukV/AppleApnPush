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

/**
 * All certificate should implement this interface
 */
interface CertificateInterface
{
    /**
     * Get path
     *
     * @return string
     */
    public function getPath();

    /**
     * Get pass phrase
     *
     * @return string
     */
    public function getPassPhrase();
}
