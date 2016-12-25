<?php

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
     */
    public function send(Request $request) : Response
    {
        $this->initializeCurlResource();
        $this->prepareCurlResourceByRequest($request);

        $content = curl_exec($this->resource);
        $statusCode = (int) curl_getinfo($this->resource, CURLINFO_HTTP_CODE);

        return new Response($statusCode, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        curl_close($this->resource);
        $this->resource = null;
    }

    /**
     * Initialize cURL resource
     */
    private function initializeCurlResource()
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
    private function prepareCurlResourceByRequest(Request $request)
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
