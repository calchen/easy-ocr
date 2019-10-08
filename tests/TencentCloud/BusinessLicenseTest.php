<?php

namespace Calchen\EasyOcr\Test\TencentCloud;

use Calchen\EasyOcr\Kernel\Support\ImageBase64;
use Calchen\EasyOcr\Models\BusinessLicense;
use Calchen\EasyOcr\Test\TestCase;
use SplFileInfo;
use Throwable;

class BusinessLicenseTest extends TestCase
{
    /**
     * 测试使用文件路径.
     */
    public function testFilePath()
    {
        $filePath = $this->getTestCaseFilePath('ocr_yyzz_02.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->businessLicense
                ->ocr($filePath)
        );
    }

    /**
     * 测试使用 base64 编码后的内容.
     *
     * @throws Throwable
     */
    public function testFileBase64String()
    {
        $filePath = $this->getTestCaseFilePath('ocr_yyzz_02.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->businessLicense
                ->ocr(ImageBase64::encode($filePath))
        );
    }

    /**
     * 测试使用二进制内容.
     */
    public function testFileContent()
    {
        $filePath = $this->getTestCaseFilePath('ocr_yyzz_02.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->businessLicense
                ->ocr(file_get_contents($filePath))
        );
    }

    /**
     * 测试 SplFileInfo 类型文件.
     */
    public function testSplFileInfo()
    {
        $filePath = $this->getTestCaseFilePath('ocr_yyzz_02.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->businessLicense
                ->ocr(new SplFileInfo($filePath))
        );
    }

    /**
     * 测试 url 类型文件.
     */
    public function testFileUrl()
    {
        $filePath = 'http://chenky-public-resource.chenky.cn/calchen/easy-ocr/master/tests/examples/ocr_yyzz_02.jpg';
        $this->checkResult(
            $this->tencentCloud()
                ->businessLicense
                ->ocr($filePath)
        );
    }

    /**
     * 校验结果.
     *
     * @param BusinessLicense $result
     */
    private function checkResult(BusinessLicense $result)
    {
        $this->assertSame('110000012345678', $result->getCode());
        $this->assertSame('深圳市腾讯计算机系统有限公司', $result->getName());
        $this->assertSame('有限责任公司', $result->getType());
        $this->assertSame('深圳市南山区高新区高新南一路飞亚达大厦', $result->getAddress());
        $this->assertSame('艾米', $result->getLegalPerson());
        $this->assertSame('人民币00000000万元整', $result->getRegisteredCapital());
        $this->assertTrue($result->getIsLongTermValid());
        $this->assertSame('计算机软、硬件的设计、技术开发、销售(不含专营、专控、专卖商品及限制项目);数据库及计算机网络服务;国内商业、物资供销业(不含专营、专控、专卖商品)',
            $result->getScope());
    }
}
