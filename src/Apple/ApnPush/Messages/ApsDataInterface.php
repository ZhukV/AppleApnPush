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

use Apple\ApnPush\PayloadFactory\PayloadDataInterface;

/**
 * Interface for control Aps data in iOS message
 */
interface ApsDataInterface extends PayloadDataInterface
{
    /**
     * Set message body
     *
     * @param string $body
     */
    public function setBody($body);

    /**
     * Get body
     *
     * @return mixed
     */
    public function getBody();

    /**
     * Set body localize
     *
     * @param string $localizeKey
     * @param array $localizeParams
     */
    public function setBodyLocalize($localizeKey, array $localizeParams);

    /**
     * Set custom
     *
     * @param array $customBody
     */
    public function setBodyCustom(array $customBody);

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
     * Set badge
     *
     * @param integer $badge
     */
    public function setBadge($badge);

    /**
     * Get badge
     *
     * @return integer
     */
    public function getBadge();
}