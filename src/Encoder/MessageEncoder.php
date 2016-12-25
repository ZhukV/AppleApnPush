<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Encoder;

use Apple\ApnPush\Model\ApsData;
use Apple\ApnPush\Model\Message;

/**
 * The encoder for encode notification message to string for next send to Apple Push Notification Service
 */
class MessageEncoder implements MessageEncoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function encode(Message $message) : string
    {
        $data = [
            'aps' => $this->convertApsDataToArray($message->getApsData()),
        ];

        $data = array_merge($message->getCustomData(), $data);

        return json_encode($data);
    }

    /**
     * Convert APS data to array
     *
     * @param ApsData $apsData
     *
     * @return array
     */
    private function convertApsDataToArray(ApsData $apsData) : array
    {
        $data = [];

        if ($apsData->getBodyCustom()) {
            $data['alert'] = $apsData->getBodyCustom();
        } else {
            $data['alert'] = $apsData->getBody();
        }

        if ($apsData->getSound()) {
            $data['sound'] = $apsData->getSound();
        }

        if ($apsData->getBadge()) {
            $data['badge'] = $apsData->getBadge();
        }

        if ($apsData->getCategory()) {
            $data['category'] = $apsData->getCategory();
        }

        if ($apsData->isContentAvailable()) {
            $data['content-available'] = 1;
        }

        return $data;
    }
}
