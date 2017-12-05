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
namespace Apple\ApnPush\Exception\SendNotification;

use Apple\ApnPush\Protocol\Http\Request;

/**
 * Abstract exception for control all errors in send notification processes.
 */
abstract class SendNotificationException extends \Exception
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     * @return SendNotificationException
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
