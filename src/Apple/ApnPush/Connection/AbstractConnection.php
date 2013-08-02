<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
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
     * @var array
     */
    protected $readTime = array(1, 0);

    /**
     * Construct
     *
     * @param string  $certificateFile
     * @param string  $certificatePassPhrase
     * @param boolean $sandboxMode
     */
    public function __construct($certificateFile = null, $certificatePassPhrase = null, $sandboxMode = false)
    {
        if (null !== $certificateFile) {
            $this->setCertificateFile($certificateFile);
        }

        if (null !== $certificatePassPhrase) {
            $this->setCertificatePassPhrase($certificatePassPhrase);
        }

        if (null !== $sandboxMode) {
            $this->setSandboxMode($sandboxMode);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setReadTime($second, $milisecond = 0)
    {
        if (!is_integer($second)) {
            throw new \InvalidArgumentException(sprintf(
                'Second must be a integer value, "%s" given.',
                gettype($second)
            ));
        }

        if (!is_integer($milisecond)) {
            throw new \InvalidArgumentException(sprintf(
                'Milisecond must be a integer value, "%s" given.',
                gettype($milisecond)
            ));
        }

        $this->readTime = array($second, $milisecond);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getReadTime()
    {
        return $this->readTime;
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
     * {@inheritDoc}
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
    public function getUrl()
    {
        return $this->sandboxMode
            ? ConnectionInterface::GATEWAY_SANDBOX_PUSH_URL
            : ConnectionInterface::GATEWAY_PUSH_URL;
    }

    /**
     * {@inheritDoc}
     */
    public function getPort()
    {
        return $this->sandboxMode
            ? ConnectionInterface::GATEWAY_SANDBOX_PUSH_PORT
            : ConnectionInterface::GATEWAY_PUSH_PORT;
    }
}
