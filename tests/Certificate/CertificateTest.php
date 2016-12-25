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

use Apple\ApnPush\Certificate\Certificate;
use PHPUnit\Framework\TestCase;

class CertificateTest extends TestCase
{
    /**
     * @test
     *
     * @expectedException \Apple\ApnPush\Exception\CertificateFileNotFoundException
     * @expectedExceptionMessage The certificate file "/path/to/missing/certificate.pem" was not found.
     */
    public function shouldFailIfCertificateNotFound()
    {
        new Certificate('/path/to/missing/certificate.pem', '');
    }

    /**
     * @test
     */
    public function shouldSuccessCreate()
    {
        $tmpDir = sys_get_temp_dir();
        $file = $tmpDir.'/'.md5(uniqid(random_int(0, 9999), true)).'.pem';
        touch($file);

        $certificate = new Certificate($file, 'pass-phrase');

        self::assertEquals($file, $certificate->getPath());
        self::assertEquals('pass-phrase', $certificate->getPassPhrase());

        unlink($file);
    }
}
