<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Messages;

/**
 * Interface for control iOS message
 */
interface MessageInterface extends PayloadDataInterface
{
    /**
     * Set identifier
     *
     * @param integer $identifier
     */
    public function setIdentifier($identifier);

    /**
     * Get identifier
     *
     * @return integet
     */
    public function getIdentifier();

    /**
     * Set device token
     *
     * @param string $deviceToken
     */
    public function setDeviceToken($deviceToken);

    /**
     * Get device token
     *
     * @return string
     */
    public function getDeviceToken();

    /**
     * Set body
     *
     * @param string $bodyMessage
     */
    public function setBody($bodyMessage);

    /**
     * Get body
     *
     * @return string
     */
    public function getBody();

    /**
     * Set APS data
     *
     * @param ApsDataInterface $apsData
     */
    public function setApsData(ApsDataInterface $apsData);

    /**
     * Get APS data
     *
     * @return ApsData
     */
    public function getApsData();

    /**
     * Set custom data
     *
     * @param array $customData
     */
    public function setCustomData(array $customData);

    /**
     * Add custom data
     *
     * @param string $customData
     */
    public function addCustomData($dataKey, $dataValue);

    /**
     * Set expires
     *
     * @param \DateTime $expires
     */
    public function setExpires(\DateTime $expires);

    /**
     * Get expire
     *
     * @return \DateTime
     */
    public function getExpires();
}