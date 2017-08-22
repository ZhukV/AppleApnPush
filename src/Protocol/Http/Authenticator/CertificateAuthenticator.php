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

namespace Apple\ApnPush\Protocol\Http\Authenticator;

use Apple\ApnPush\Certificate\CertificateInterface;
use Apple\ApnPush\Protocol\Http\Request;

/**
 * Authenticate request via certificate
 */
class CertificateAuthenticator implements AuthenticatorInterface
{
    /**
     * @var CertificateInterface
     */
    private $certificate;

    /**
     * Constructor.
     *
     * @param CertificateInterface $certificate
     */
    public function __construct(CertificateInterface $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Request $request): Request
    {
        $request = $request->withCertificate($this->certificate->getPath());
        $request = $request->withCertificatePassPhrase($this->certificate->getPassPhrase());

        return $request;
    }
}
