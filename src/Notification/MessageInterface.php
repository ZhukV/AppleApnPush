<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

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
     * @return integer
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
     * Set body localize
     *
     * @param string $localizeKey
     * @param array  $params
     */
    public function setBodyLocalize($localizeKey, array $params = array());

    /**
     * Get body
     *
     * @return string
     */
    public function getBody();

    /**
     * Set category
     *
     * @param string $category
     */
    public function setCategory($category);

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory();

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
     * @param string $dataKey
     * @param mixed  $dataValue
     */
    public function addCustomData($dataKey, $dataValue);

    /**
     * Get custom data
     *
     * @return array
     */
    public function getCustomData();

    /**
     * Set badge
     *
     * @param int $badge
     */
    public function setBadge($badge);

    /**
     * Get badge
     *
     * @return int
     */
    public function getBadge();

    /**
     * Set sound
     *
     * @param string $sound
     */
    public function setSound($sound);

    /**
     * Get sound
     *
     * @return string
     */
    public function getSound();

    /**
     * Set content available
     *
     * @param bool $contentAvailable
     */
    public function setContentAvailable($contentAvailable);

    /**
     * Is content available
     * 
     * @return bool
     */
    public function isContentAvailable();

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
