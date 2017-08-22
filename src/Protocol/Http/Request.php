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

namespace Apple\ApnPush\Protocol\Http;

/**
 * Object for presentation http request
 */
class Request
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $certificate = '';

    /**
     * @var string
     */
    private $certificatePassPhrase = '';

    /**
     * Constructor.
     *
     * @param string $url
     * @param string $content
     * @param array  $headers
     */
    public function __construct(string $url, string $content, array $headers = [])
    {
        $this->url = $url;
        $this->headers = $headers;
        $this->content = $content;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Add or replace header
     *
     * @param string $name
     * @param string $value
     *
     * @return Request
     */
    public function withHeader(string $name, string $value): Request
    {
        $cloned = clone $this;

        $cloned->headers[$name] = $value;

        return $cloned;
    }

    /**
     * Add multiple headers
     *
     * @param array $headers
     *
     * @return Request
     */
    public function withHeaders(array $headers): Request
    {
        $cloned = clone $this;

        foreach ($headers as $name => $value) {
            $cloned = $cloned->withHeader($name, $value);
        }

        return $cloned;
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Use certificate for send request
     *
     * @param string $certificate
     *
     * @return Request
     */
    public function withCertificate(string $certificate): Request
    {
        $cloned = clone $this;

        $cloned->certificate = $certificate;

        return $cloned;
    }

    /**
     * Get certificate
     *
     * @return string
     */
    public function getCertificate(): string
    {
        return $this->certificate;
    }

    /**
     * Use pass phrase of certificate for send request
     *
     * @param string $passPhrase
     *
     * @return Request
     */
    public function withCertificatePassPhrase(string $passPhrase): Request
    {
        $cloned = clone $this;

        $cloned->certificatePassPhrase = $passPhrase;

        return $cloned;
    }

    /**
     * Get certificate pass phrase
     *
     * @return string
     */
    public function getCertificatePassPhrase(): string
    {
        return $this->certificatePassPhrase;
    }
}
