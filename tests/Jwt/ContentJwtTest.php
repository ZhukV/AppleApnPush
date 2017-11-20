<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Jwt;

use Apple\ApnPush\Jwt\ContentJwt;
use PHPUnit\Framework\TestCase;

class ContentJwtTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate()
    {
        $jwt = new ContentJwt('team id', 'key', 'jwt certificate content', sys_get_temp_dir());

        self::assertEquals('team id', $jwt->getTeamId());
        self::assertEquals('key', $jwt->getKey());

        $path = $jwt->getPath();
        self::assertNotEmpty($path);
        self::assertFileExists($path);
        self::assertEquals('jwt certificate content', file_get_contents($path));

        unset($jwt);

        self::assertFileNotExists($path);
    }
}
