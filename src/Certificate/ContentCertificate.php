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

namespace Apple\ApnPush\Certificate;

/**
 * Certificate with content. You can use this certificate, if you save content to database
 * or another storage. Before get certificate file, this system create a temporary file certificate
 * and returns path to it. After close connection (destruct), the temporary file
 * will be removed.
 */
class ContentCertificate implements CertificateInterface
{
    private string $content;
    private string $passPhrase;
    private string $tmpDir;
    private ?string $certificateFilePath = null;

    public function __construct(string $content, string $passPhrase, string $tmpDir)
    {
        $this->content = $content;
        $this->passPhrase = $passPhrase;
        $this->tmpDir = $tmpDir;
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

    public function getPassPhrase(): string
    {
        return $this->passPhrase;
    }

    public function __destruct()
    {
        if ($this->certificateFilePath) {
            $this->removeTemporaryFile($this->certificateFilePath);
        }
    }

    private function createTemporaryFile(): string
    {
        $tmpDir = $this->tmpDir;

        $tmpFileName = \md5(\uniqid((string) \mt_rand(), true)).'.pem';

        $tmpFilePath = $tmpDir.'/'.$tmpFileName;

        $errorCode = $errorMessage = null;

        \set_error_handler(static function (int $errCode, string $errMessage) use (&$errorCode, &$errorMessage) {
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
