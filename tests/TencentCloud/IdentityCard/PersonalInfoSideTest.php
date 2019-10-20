<?php

namespace Calchen\EasyOcr\Test\TencentCloud\IdentityCard;

use Calchen\EasyOcr\Exception\ErrorCodes;
use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Exception\TencentCloudException;
use Calchen\EasyOcr\Kernel\Contract\IdentityCardClient;
use Calchen\EasyOcr\Kernel\Support\ImageBase64;
use Calchen\EasyOcr\Models\IdentityCard;
use Calchen\EasyOcr\TencentCloud\IdentityCard\Config;
use Calchen\EasyOcr\Test\TestCase;
use Carbon\Carbon;
use Exception;
use SplFileInfo;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use Throwable;

class PersonalInfoSideTest extends TestCase
{
    /**
     * 测试使用文件路径.
     */
    public function testFilePath()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->personalInfoSideOcr($filePath)
        );
    }

    /**
     * 测试使用 base64 编码后的内容.
     *
     * @throws Throwable
     */
    public function testFileBase64String()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->personalInfoSideOcr(ImageBase64::encode($filePath))
        );
    }

    /**
     * 测试使用二进制内容.
     */
    public function testFileContent()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->personalInfoSideOcr(file_get_contents($filePath))
        );
    }

    /**
     * 测试 SplFileInfo 类型文件.
     */
    public function testSplFileInfo()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->personalInfoSideOcr(new SplFileInfo($filePath))
        );
    }

    /**
     * 测试 url 类型文件.
     */
    public function testFileUrl()
    {
        $filePath = 'http://chenky-public-resource.chenky.cn/calchen/easy-ocr/master/tests/examples/identityCard/personalInfoSide/icon_id_01.jpg';
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->personalInfoSideOcr($filePath)
        );
    }

    /**
     * 测试设置错身份证正反面.
     */
    public function testWrongSideParameter()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        try {
            $this->tencentCloud()
                ->identityCard
                ->nationalEmblemSideOcr($filePath);
        } catch (Exception $e) {
            $this->assertInstanceOf(TencentCloudException::class, $e);
            $this->assertEquals(ErrorCodes::TENCENT_CLOUD_API_EXCEPTION, $e->getCode());
            $this->assertEquals('{"message":"Ocr识别失败","code":0}', $e->getMessage());
        }
    }

    /**
     * 测试非身份证正面文件.
     */
    public function testUnidentifiableFile()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_ocr_card_1.jpg');
        try {
            $this->tencentCloud()
                ->identityCard
                ->personalInfoSideOcr($filePath);
        } catch (Exception $e) {
            $this->assertInstanceOf(TencentCloudException::class, $e);
            $this->assertEquals(ErrorCodes::TENCENT_CLOUD_API_EXCEPTION, $e->getCode());
            $this->assertEquals('{"message":"Ocr识别失败","code":0}', $e->getMessage());
        }
    }

    /**
     * 测试 Ocr 方法.
     */
    public function testOcrFunction()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->ocr($filePath, IdentityCardClient::CARD_SIDES[0])
        );
    }

    /**
     * 测试 Ocr 方法证件正反面参数不合法.
     */
    public function testOcrFunctionCardSide()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        try {
            $this->tencentCloud()
                ->identityCard
                ->ocr($filePath, 'unknown');
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals(ErrorCodes::IDENTITY_CARD_SIDE_ARGUMENT_INVALID, $e->getCode());
        }
    }

    /**
     * 测试身份证照片裁剪.
     *
     * @throws Throwable
     */
    public function testConfigCropIdCard()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        $config = new Config();
        $config->setCropIdCard(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->personalInfoSideOcr($filePath, $config);

        $resource = ImageBase64::decode($result->getExtra()['IdCard']);
        $this->assertEquals('stream', get_resource_type($resource));

        $tempFileFullPath = $this->generateTempImageFilePath(__DIR__, __NAMESPACE__, __CLASS__, __FUNCTION__);
        file_put_contents($tempFileFullPath, $resource);
        $this->assertFileExists($tempFileFullPath);
        $this->assertGreaterThan(0, filesize($tempFileFullPath));
    }

    /**
     * 测试人像照片裁剪.
     *
     * @throws Throwable
     */
    public function testConfigCropPortrait()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        $config = new Config();
        $config->setCropPortrait(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->personalInfoSideOcr($filePath, $config);

        $resource = ImageBase64::decode($result->getExtra()['Portrait']);
        $this->assertEquals('stream', get_resource_type($resource));

        $tempFileFullPath = $this->generateTempImageFilePath(__DIR__, __NAMESPACE__, __CLASS__, __FUNCTION__);
        file_put_contents($tempFileFullPath, $resource);
        $this->assertFileExists($tempFileFullPath);
        $this->assertGreaterThan(0, filesize($tempFileFullPath));
    }

    /**
     * 测试复印件告警.
     */
    public function testCopyWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_02-1.png');
        $config = new Config();
        $config->setCopyWarn(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->personalInfoSideOcr($filePath, $config);

        $this->assertTrue($result->getExtra()['WarnInfos']['CopyWarn']);
    }

    /**
     * 测试边框和框内遮挡告警.
     */
    public function testBorderCheckWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_01.jpg');
        $config = new Config();
        $config->setBorderCheckWarn(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->personalInfoSideOcr($filePath, $config);

        $this->assertTrue($result->getExtra()['WarnInfos']['BorderCheckWarn']);
    }

    /**
     * 测试翻拍告警.
     */
    public function testReshootWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_05.jpg');
        $config = new Config();
        $config->setReshootWarn(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->personalInfoSideOcr($filePath, $config);

        $this->assertTrue($result->getExtra()['WarnInfos']['ReshootWarn']);
    }

    /**
     * 测试PS检测告警.
     */
    public function testDetectPsWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/icon_id_05.jpg');
        $config = new Config();
        $config->setDetectPsWarn(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->personalInfoSideOcr($filePath, $config);

        $this->assertTrue($result->getExtra()['WarnInfos']['DetectPsWarn']);
    }

    /**
     * 测试临时身份证告警.
     */
    public function testTempIdWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/th.jpg');
        $config = new Config();
        $config->setTempIdWarn(true);

        try {
            $this->tencentCloud()
                ->identityCard
                ->personalInfoSideOcr($filePath, $config);
        } catch (Exception $e) {
            $this->assertInstanceOf(\Calchen\EasyOcr\Exception\Exception::class, $e);
            $this->assertEquals(ErrorCodes::TENCENT_CLOUD_IDENTITY_CARD_IS_TEMPORARY, $e->getCode());
        }
    }

    /**
     * 测试在设置了 config 但使用了不正确的文件处理.
     *
     * @throws Throwable
     */
    public function testInvalidFileWithConfig()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/personalInfoSide/face_02.jpg');
        $config = new Config();
        $config->setCopyWarn(true);
        try {
            $this->tencentCloud()
                ->identityCard
                ->personalInfoSideOcr($filePath, $config);
        } catch (Exception $e) {
            $this->assertInstanceOf(\Calchen\EasyOcr\Exception\Exception::class, $e);
            $this->assertEquals(ErrorCodes::TENCENT_CLOUD_IDENTITY_CARD_OCR_EMPTY_DATA, $e->getCode());
        }
    }

    /**
     * 校验结果.
     *
     * @param IdentityCard $result
     */
    private function checkResult(IdentityCard $result)
    {
        $this->assertSame('李明', $result->getName());
        $this->assertSame('male', $result->getGender());
        $this->assertSame('汉', $result->getNation());
        $birthday = Carbon::create(1987, 1, 1, 0, 0, 0);
        $this->assertSame($birthday->toDateTimeString(), $result->getBirthday()->toDateTimeString());
        $this->assertSame('北京市石景山区高新技术园腾讯大楼', $result->getAddress());
        $this->assertSame('440524198701010014', $result->getNumber());
    }
}
