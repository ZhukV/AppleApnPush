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
 * Object for presentation http response
 */
class Response
{
    /**
     * @var float
     */
    private $time;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $content;

    /**
     * Constructor.
     *
     * @param int    $statusCode
     * @param string $content
     * @param float  $requestTime
     */
    public function __construct(int $statusCode, string $content, float $requestTime)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->time = $requestTime;
    }

    /**
     * Get status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get request time
     *
     * @return float
     */
    public function getTime(): float
    {
        return $this->time;
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
}
