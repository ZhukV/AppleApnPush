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

class Request
{
    private string $url;
    private array $headers;
    private string $content;
    private string $certificate = '';
    private string $certificatePassPhrase = '';

    public function __construct(string $url, string $content, array $headers = [])
    {
        $this->url = $url;
        $this->headers = $headers;
        $this->content = $content;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function withHeader(string $name, string $value): self
    {
        $cloned = clone $this;

        $cloned->headers[$name] = $value;

        return $cloned;
    }

    public function withHeaders(array $headers): self
    {
        $cloned = clone $this;

        foreach ($headers as $name => $value) {
            $cloned = $cloned->withHeader($name, $value);
        }

        return $cloned;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function withCertificate(string $certificate): self
    {
        $cloned = clone $this;

        $cloned->certificate = $certificate;

        return $cloned;
    }

    public function getCertificate(): string
    {
        return $this->certificate;
    }

    public function withCertificatePassPhrase(string $passPhrase): self
    {
        $cloned = clone $this;

        $cloned->certificatePassPhrase = $passPhrase;

        return $cloned;
    }

    public function getCertificatePassPhrase(): string
    {
        return $this->certificatePassPhrase;
    }
}
