<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Certificate;

use Apple\ApnPush\Certificate\ContentCertificate;
use PHPUnit\Framework\TestCase;

class ContentCertificateTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreateContentCertificate()
    {
        $certificate = new ContentCertificate('content', 'pass', sys_get_temp_dir());

        $filePath = $certificate->getPath();
        $fileContent = file_get_contents($filePath);

        self::assertEquals('content', $fileContent);
        self::assertEquals('pass', $certificate->getPassPhrase());
    }
}
