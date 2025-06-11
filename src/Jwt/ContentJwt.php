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
    private string $teamId;
    private string $key;
    private string $content;
    private string $tmpDir;
    private ?string $certificateFilePath = null;

    public function __construct(string $teamId, string $key, string $content, string $tmpDir)
    {
        $this->teamId = $teamId;
        $this->key = $key;
        $this->content = $content;
        $this->tmpDir = $tmpDir;
    }

    public function __destruct()
    {
        if ($this->certificateFilePath) {
            $this->removeTemporaryFile($this->certificateFilePath);
        }
    }

    public function getTeamId(): string
    {
        return $this->teamId;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getPath(): string
    {
        if ($this->certificateFilePath) {
            $this->removeTemporaryFile($this->certificateFilePath);
        }

        $this->certificateFilePath = $this->createTemporaryFile();
        \file_put_contents($this->certificateFilePath, $this->content);

        return $this->certificateFilePath;
    }

    private function createTemporaryFile(): string
    {
        $tmpDir = $this->tmpDir;

        $tmpFileName = \md5(\uniqid((string) \mt_rand(), true)).'.p8';

        $tmpFilePath = $tmpDir.'/'.$tmpFileName;

        $errorCode = $errorMessage = null;

        \set_error_handler(static function ($errCode, $errMessage) use (&$errorCode, &$errorMessage): bool {
            $errorCode = $errCode;
            $errorMessage = $errMessage;

            return true;
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

    private function removeTemporaryFile(string $filePath): void
    {
        // Set custom error handler for suppress error
        \set_error_handler(static function () {
            return true;
        });

        \unlink($filePath);

        \restore_error_handler();
    }
}
