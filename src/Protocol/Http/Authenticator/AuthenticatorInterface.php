<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Protocol\Http\Authenticator;

use Apple\ApnPush\Protocol\Http\Request;

/**
 * All HTTP authenticators should implement this interface
 */
interface AuthenticatorInterface
{
    /**
     * Authenticate request
     *
     * @param Request $request
     *
     * @return Request
     */
    public function authenticate(Request $request) : Request;
}
