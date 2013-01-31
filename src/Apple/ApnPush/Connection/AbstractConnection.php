<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Connection;

/**
 * Abstract core connection for Apple push notification
 */
abstract class AbstractConnection implements ConnectionInterface
{
    /**
     * @var string
     */
    protected $certificateFile;

    /**
     * @var string
     */
    protected $certificatePassPhrase;

    /**
     * @var boolean
     */
    protected $sandboxMode;

    /**
     * Construct
     *
     * @param string $certificateFile
     * @param string $certificatePassPhrase
     * @param boolean $sandboxMode
     */
    public function __construct($certificateFile = NULL, $certificatePassPhrase = NULL, $sandboxMode = FALSE)
    {
        if ($certificateFile !== NULL) {
            $this->setCertificateFile($certificateFile);
        }

        if ($certificatePassPhrase !== NULL) {
            $this->setCertificatePassPhrase($certificatePassPhrase);
        }

        if ($sandboxMode !== NULL) {
            $this->setSandboxMode($sandboxMode);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setCertificateFile($certificateFile)
    {
        if (!file_exists($certificateFile)) {
            throw new \InvalidArgumentException(sprintf(
                'Not found ceritificate file "%s".',
                $certificateFile
            ));
        }

        if (!is_readable($certificateFile)) {
            throw new \InvalidArgumentException(sprintf(
                'Can\'t read certificate file "%s".',
                $certificateFile
            ));
        }

        $this->certificateFile = $certificateFile;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCertificateFile()
    {
        return $this->certificateFile;
    }

    /**
     * {@inheritDoc}
     */
    public function setCertificatePassPhrase($certificatePassPhrase)
    {
        $this->certificatePassPhrase = $certificatePassPhrase;

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getCertificatePassPhrase()
    {
        return $this->certificatePassPhrase;
    }

    /**
     * {@inheritDoc}
     */
    public function setSandboxMode($sandboxMode)
    {
        $this->sandboxMode = (bool) $sandboxMode;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSandboxMode()
    {
        return $this->sandboxMode;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionUrl()
    {
        return $this->sandboxMode
            ? ConnectionInterface::GATEWAY_SANDBOX_PUSH_URL
            : ConnectionInterface::GATEWAY_PUSH_URL;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionPort()
    {
        return $this->sandboxMode
            ? ConnectionInterface::GATEWAY_SANDBOX_PUSH_PORT
            : ConnectionInterface::GATEWAY_PUSH_PORT;
    }
}