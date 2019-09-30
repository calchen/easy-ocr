<?php

namespace Calchen\EasyOcr\Test\Models;

use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Models\BusinessLicense;
use Calchen\EasyOcr\Test\TestCase;
use Exception;

class BusinessLicenseTest extends TestCase
{
    /**
     * 测试入参不正确
     *
     * @throws Exception
     */
    public function testInvalidArgument()
    {
        try {
            new BusinessLicense([]);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    /**
     * 测试转数组
     */
    public function testToArray()
    {
        $filePath = $this->getTestCaseFilePath('ocr_yyzz_02.jpg');
        $result = $this->tencentCloud()
            ->businessLicense
            ->ocr($filePath);
        $this->assertTrue(is_array($result->toArray()));
    }
}