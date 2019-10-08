<?php

namespace Calchen\EasyOcr\Test\Kernel\Support;

use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Kernel\Support\ImageBase64;
use Calchen\EasyOcr\Test\TestCase;
use Exception;
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

    /**
     * 测试解码非 Base64 编码的图片字符串时是否抛异常
     *
     * @throws Throwable
     */
    public function testDecodeInvalidImage()
    {
        try {
            ImageBase64::decode('not image');
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
