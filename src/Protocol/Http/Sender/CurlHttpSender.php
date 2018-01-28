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

namespace Apple\ApnPush\Protocol\Http\Sender;

use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Response;
use Apple\ApnPush\Protocol\Http\Sender\Exception\HttpSenderException;

/**
 * Send HTTP request via cURL
 */
class CurlHttpSender implements HttpSenderInterface
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * {@inheritdoc}
     *
     * @throws HttpSenderException
     */
    public function send(Request $request): Response
    {
        $this->initializeCurlResource();
        $this->prepareCurlResourceByRequest($request);

        $content = curl_exec($this->resource);

        if (false === $content) {
            throw new HttpSenderException(sprintf(
                'cURL Error [%d]: %s',
                (int) curl_errno($this->resource),
                (string) curl_error($this->resource)
            ));
        }

        $statusCode = (int) curl_getinfo($this->resource, CURLINFO_HTTP_CODE);

        return new Response($statusCode, (string) $content);
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        curl_close($this->resource);
        $this->resource = null;
    }

    /**
     * Initialize cURL resource
     */
    private function initializeCurlResource(): void
    {
        if (!$this->resource) {
            $this->resource = curl_init();

            curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->resource, CURLOPT_POST, 1);
            curl_setopt($this->resource, CURLOPT_HTTP_VERSION, 3);
        }
    }

    /**
     * Prepare cURL resource by request
     *
     * @param Request $request
     */
    private function prepareCurlResourceByRequest(Request $request): void
    {
        curl_setopt($this->resource, CURLOPT_URL, $request->getUrl());
        curl_setopt($this->resource, CURLOPT_POSTFIELDS, $request->getContent());

        if ($request->getCertificate()) {
            curl_setopt($this->resource, CURLOPT_SSLCERT, $request->getCertificate());
            curl_setopt($this->resource, CURLOPT_SSLCERTPASSWD, $request->getCertificatePassPhrase());
        }

        $inlineHeaders = [];

        foreach ($request->getHeaders() as $name => $value) {
            $inlineHeaders[] = sprintf('%s: %s', $name, $value);
        }

        curl_setopt($this->resource, CURLOPT_HTTPHEADER, $inlineHeaders);
    }
}
