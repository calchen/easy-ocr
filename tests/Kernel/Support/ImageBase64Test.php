<?php

namespace Calchen\EasyOcr\Test\Kernel\Support;

use Calchen\EasyOcr\Kernel\Support\ImageBase64;
use Calchen\EasyOcr\Test\TestCase;
use SplFileInfo;
use Throwable;

class ImageBase64Test extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testEncodeBySplFileInfo()
    {
        $path = $this->getTestCaseFilePath('icon_id_01.jpg');
        $result = ImageBase64::encode(new SplFileInfo($path));
        $this->assertStringStartsWith('data:image', $result);
    }

    /**
     * @throws Throwable
     */
    public function testEncodeByPath()
    {
        $path = $this->getTestCaseFilePath('icon_id_01.jpg');
        $result = ImageBase64::encode(file_get_contents($path));
        $this->assertStringStartsWith('data:image', $result);
    }

    /**
     * @throws Throwable
     */
    public function testEncodeByContent()
    {
        $path = $this->getTestCaseFilePath('icon_id_01.jpg');
        $result = ImageBase64::encode($path);
        $this->assertStringStartsWith('data:image', $result);
    }

    /**
     * @throws Throwable
     */
    public function testDecode()
    {
        $path = $this->getTestCaseFilePath('icon_id_01.jpg');
        $imageBase64 = ImageBase64::encode($path);
        $image = ImageBase64::decode($imageBase64);
        $this->assertSame('image/jpeg', mime_content_type($image));
    }
}