<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Model;

use Apple\ApnPush\Model\Localized;
use PHPUnit\Framework\TestCase;

class LocalizedTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate()
    {
        $localized = new Localized('some', ['key' => 'value']);

        self::assertEquals('some', $localized->getKey());
        self::assertEquals(['key' => 'value'], $localized->getArgs());
    }

    /**
     * @test
     */
    public function shouldSuccessChangeKey()
    {
        $localized = new Localized('some', ['key' => 'value']);
        $localizedChangedKey = $localized->withKey('newSome');

        self::assertEquals('newSome', $localizedChangedKey->getKey());
    }

    /**
     * @test
     */
    public function shouldSuccessChangeArgs()
    {
        $localized = new Localized('some', ['key' => 'value']);
        $localizedChangedKey = $localized->withArgs(['newKey' => 'newValue']);

        self::assertEquals(['newKey' => 'newValue'], $localizedChangedKey->getArgs());
        self::assertEquals('some', $localizedChangedKey->getKey());
    }
}
