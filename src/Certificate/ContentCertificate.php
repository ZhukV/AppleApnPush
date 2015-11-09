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
 * Certificate with content. You can use this certificate, if you save content to database
 * or another storage. Before get certificate file, this system create a temporary file certificate
 * and returns path to it. After close connection (destruct), the temporary file
 * will be removed.
 */
class ContentCertificate implements CertificateInterface
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $passPhrase;

    /**
     * @var string
     */
    private $tmpDir;

    /**
     * @var string
     */
    private $certificateFilePath;

    /**
     * Construct
     *
     * @param string $content
     * @param string $passPhrase
     * @param string $tmpDir
     */
    public function __construct($content, $passPhrase, $tmpDir = null)
    {
        $this->content = $content;
        $this->passPhrase = $passPhrase;
        $this->tmpDir = $tmpDir;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()
    {
        if ($this->certificateFilePath) {
            $this->removeTemporaryFile($this->certificateFilePath);
        }

        $this->certificateFilePath = $this->createTemporaryFile();
        file_put_contents($this->certificateFilePath, $this->content);

        return $this->certificateFilePath;
    }

    /**
     * {@inheritDoc}
     */
    public function getPassPhrase()
    {
        return $this->passPhrase;
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
     * Create a temporary file
     *
     * @return string Path to temporary file
     */
    private function createTemporaryFile()
    {
        $tmpDir = $this->tmpDir;

        if (!$tmpDir) {
            $tmpDir = sys_get_temp_dir();
        }

        $tmpFileName = md5(uniqid(mt_rand(), true)) . '.pem';

        $tmpFilePath = $tmpDir . '/' . $tmpFileName;

        $errorCode = $errorMessage = null;

        set_error_handler(function ($errCode, $errMessage) use (&$errorCode, &$errorMessage) {
            $errorCode = $errCode;
            $errorMessage = $errMessage;
        });

        if (!file_exists($tmpDir)) {
            mkdir($tmpDir, 0600, true);

            if ($errorCode || $errorMessage) {
                restore_error_handler();
                // Error create directory
                throw new \RuntimeException(sprintf(
                    'Can not create temporary directory "%s". Error: %s [%d].',
                    $errorMessage ?: 'Undefined',
                    $errorCode ?: '0'
                ));
            }
        }

        touch($tmpFilePath);

        if ($errorCode || $errorMessage) {
            restore_error_handler();
            // Error create file
            throw new \RuntimeException(sprintf(
                'Can not create temporary certificate file "%s". Error: %s [%d].',
                $errorMessage ?: 'Undefied',
                $errorCode ?: '0'
            ));
        }

        restore_error_handler();

        return $tmpFilePath;
    }

    /**
     * Remove temporary file
     *
     * @param string $filePath
     */
    private function removeTemporaryFile($filePath)
    {
        // Set custom error handler for suppress error
        set_error_handler(function () {
        });

        unlink($filePath);

        restore_error_handler();
    }
}
