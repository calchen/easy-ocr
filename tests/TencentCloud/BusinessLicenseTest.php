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
        $filePath = $this->getTestCaseFilePath('wKhQtFNLruGEX31pAAAAAM8RGA8186.jpg');
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
        $filePath = $this->getTestCaseFilePath('wKhQtFNLruGEX31pAAAAAM8RGA8186.jpg');
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
        $filePath = $this->getTestCaseFilePath('wKhQtFNLruGEX31pAAAAAM8RGA8186.jpg');
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
        $filePath = $this->getTestCaseFilePath('wKhQtFNLruGEX31pAAAAAM8RGA8186.jpg');
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
        $filePath = 'http://chenky-public-resource.chenky.cn/calchen/easy-ocr/master/tests/examples/wKhQtFNLruGEX31pAAAAAM8RGA8186.jpg';
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
        $this->assertSame('440106000918690', $result->getCode());
        $this->assertSame('广州诺正网络科技有限公司', $result->getName());
        $this->assertSame('有限责任公司(自然人投资或控股)', $result->getType());
        $this->assertSame('广州市天河区牛利岗大街85号5楼(部位572房)', $result->getAddress());
        $this->assertSame('詹俊孟', $result->getLegalPerson());
        $this->assertSame('壹佰万元整', $result->getRegisteredCapital());
        $this->assertTrue($result->getIsLongTermValid());
        $this->assertSame('研究和试验发展(具体经营项目请登录广州市商事主体信息公示平台查询.依法须经批准的项目,经相关部门批准后方可开展经营活动。)',
            $result->getScope());
    }
}
