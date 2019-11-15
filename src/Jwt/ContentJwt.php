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

/**
 * The JWT token with content for certificate. You can use this certificate, if you save content to database
 * or another storage. Before get certificate file, this system create a temporary file certificate
 * and returns path to it. After close connection (destruct), the temporary file
 * will be removed.
 */
class ContentJwt implements JwtInterface
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
    private $content;

    /**
     * @var string
     */
    private $tmpDir;

    /**
     * @var string
     */
    private $certificateFilePath;

    /**
     * Constructor.
     *
     * @param string $teamId
     * @param string $key
     * @param string $content
     * @param string $tmpDir
     */
    public function __construct(string $teamId, string $key, string $content, string $tmpDir)
    {
        $this->teamId = $teamId;
        $this->key = $key;
        $this->content = $content;
        $this->tmpDir = $tmpDir;
    }

    /**
     * Implement __destruct
     * Remove temporary file
     */
    public function __destruct()
    {
        if ($this->certificateFilePath) {
            $this->removeTemporaryFile($this->certificateFilePath);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTeamId(): string
    {
        return $this->teamId;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        if ($this->certificateFilePath) {
            $this->removeTemporaryFile($this->certificateFilePath);
        }

        $this->certificateFilePath = $this->createTemporaryFile();
        \file_put_contents($this->certificateFilePath, $this->content);

        return $this->certificateFilePath;
    }

    /**
     * Create a temporary file
     *
     * @return string Path to temporary file
     *
     * @throws \RuntimeException
     */
    private function createTemporaryFile(): string
    {
        $tmpDir = $this->tmpDir;

        $tmpFileName = \md5(\uniqid((string) \mt_rand(), true)).'.p8';

        $tmpFilePath = $tmpDir.'/'.$tmpFileName;

        $errorCode = $errorMessage = null;

        \set_error_handler(function ($errCode, $errMessage) use (&$errorCode, &$errorMessage) {
            $errorCode = $errCode;
            $errorMessage = $errMessage;
        });

        if (!\file_exists($tmpDir)) {
            \mkdir($tmpDir, 0600, true);

            if ($errorCode || $errorMessage) {
                \restore_error_handler();

                // Error create directory
                throw new \RuntimeException(sprintf(
                    'Can not create temporary directory "%s". Error: %s [%d].',
                    $tmpDir,
                    $errorMessage ?: 'Undefined',
                    $errorCode ?: '0'
                ));
            }
        }

        \touch($tmpFilePath);

        if ($errorCode || $errorMessage) {
            \restore_error_handler();

            // Error create file
            throw new \RuntimeException(sprintf(
                'Can not create temporary certificate file "%s". Error: %s [%d].',
                $tmpFilePath,
                $errorMessage ?: 'Undefined',
                $errorCode ?: '0'
            ));
        }

        \restore_error_handler();

        return $tmpFilePath;
    }

    /**
     * Remove temporary file
     *
     * @param string $filePath
     */
    private function removeTemporaryFile($filePath): void
    {
        // Set custom error handler for suppress error
        \set_error_handler(function () {
        });

        \unlink($filePath);

        \restore_error_handler();
    }
}
