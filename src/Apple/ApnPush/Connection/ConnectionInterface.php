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
 * Interface for connection to Apple push servers
 */
interface ConnectionInterface
{
    /**
     * Default URLs
     */
    const GATEWAY_PUSH_URL              =   'gateway.push.apple.com';
    const GATEWAY_PUSH_PORT             =   2195;

    const GATEWAY_SANDBOX_PUSH_URL      =   'gateway.sandbox.push.apple.com';
    const GATEWAY_SANDBOX_PUSH_PORT     =   2195;

    /**
     * Set certificate
     *
     * @param string $certificateFile
     */
    public function setCertificateFile($certificateFile);

    /**
     * Get certificate file
     *
     * @return string
     */
    public function getCertificateFile();

    /**
     * Set certificate pass phrase
     *
     * @param string $certificatePassPhrase
     */
    public function setCertificatePassPhrase($certificatePassPhrase);

    /**
     * Get certificate pass phrase
     *
     * @return string
     */
    public function getCertificatePassPhrase();

    /**
     * Create connection
     */
    public function createConnection();

    /**
     * Close connection
     */
    public function closeConnection();

    /**
     * Is connection
     *
     * @return boolean
     */
    public function isConnection();

    /**
     * Set sandbox mode
     *
     * @param boolean $sandbox
     */
    public function setSandboxMode($sandbox);

    /**
     * Get sandbox mode
     *
     * @return boolean
     */
    public function getSandboxMode();

    /**
     * Write data to connection
     *
     * @param string $data
     * @param integer $length
     */
    public function write($data, $length = NULL);

    /**
     * Read data from connection
     *
     * @return string
     */
    public function read($length);

    /**
     * Get connection URL
     *
     * @return string
     */
    public function getConnectionUrl();

    /**
     * Get connection port
     *
     * @return integer
     */
    public function getConnectionPort();
}