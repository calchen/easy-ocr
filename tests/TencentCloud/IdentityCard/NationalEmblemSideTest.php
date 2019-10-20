<?php

namespace Calchen\EasyOcr\Test\TencentCloud\IdentityCard;

use Calchen\EasyOcr\Exception\ErrorCodes;
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

class NationalEmblemSideTest extends TestCase
{
    /**
     * 测试使用文件路径.
     */
    public function testFilePath()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_03.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->nationalEmblemSideOcr($filePath)
        );
    }

    /**
     * 测试使用 base64 编码后的内容.
     *
     * @throws \Throwable
     */
    public function testFileBase64String()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_03.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->nationalEmblemSideOcr(ImageBase64::encode($filePath))
        );
    }

    /**
     * 测试使用二进制内容.
     */
    public function testFileContent()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_03.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->nationalEmblemSideOcr(file_get_contents($filePath))
        );
    }

    /**
     * 测试 SplFileInfo 类型文件.
     */
    public function testSplFileInfo()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_03.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->nationalEmblemSideOcr(new SplFileInfo($filePath))
        );
    }

    /**
     * 测试 url 类型文件.
     */
    public function testFileUrl()
    {
        $filePath = 'http://chenky-public-resource.chenky.cn/calchen/easy-ocr/master/tests/examples/identityCard/nationalEmblemSide/icon_id_03.jpg';
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->nationalEmblemSideOcr($filePath)
        );
    }

    /**
     * 测试设置错身份证正反面.
     */
    public function testWrongSideParameter()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_03.jpg');
        try {
            $r = $this->tencentCloud()
                ->identityCard
                ->personalInfoSideOcr($filePath);
        } catch (Exception $e) {
            $this->assertInstanceOf(TencentCloudException::class, $e);
            $this->assertEquals(ErrorCodes::TENCENT_CLOUD_API_EXCEPTION, $e->getCode());
            $this->assertEquals('{"message":"Ocr识别失败","code":0}', $e->getMessage());
        }
    }

    /**
     * 测试非身份证反面.
     */
    public function testUnidentifiableFile()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_ocr_card_1.jpg');
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
     * 测试 Ocr 方法.
     */
    public function testOcrFunction()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_03.jpg');
        $this->checkResult(
            $this->tencentCloud()
                ->identityCard
                ->ocr($filePath, IdentityCardClient::CARD_SIDES[1])
        );
    }

    /**
     * 测试长期有效的证件.
     */
    public function testLongTermValid()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_06.jpg');
        $result = $this->tencentCloud()
            ->identityCard
            ->nationalEmblemSideOcr($filePath);

        $this->assertSame('东港市公安局', $result->getIssuingAuthority());
        $validStartAt = Carbon::create(2014, 7, 9, 0, 0, 0);
        $this->assertSame($validStartAt->toDateTimeString(), $result->getValidStartAt()->toDateTimeString());
        $this->assertTrue($result->getIsLongTermValid());
    }

    /**
     * 测试身份证照片裁剪.
     *
     * @throws Throwable
     */
    public function testConfigCropIdCard()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_03.jpg');
        $config = new Config();
        $config->setCropIdCard(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->nationalEmblemSideOcr($filePath, $config);

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
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_03.jpg');
        $config = new Config();
        $config->setCropPortrait(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->nationalEmblemSideOcr($filePath, $config);

        $resource = ImageBase64::decode($result->getExtra()['Portrait']);
        $this->assertEquals('stream', get_resource_type($resource));

        $tempFileFullPath = $this->generateTempImageFilePath(__DIR__, __NAMESPACE__, __CLASS__, __FUNCTION__);
        file_put_contents($tempFileFullPath, $resource);
        $this->assertFileExists($tempFileFullPath);
        $this->assertEquals(0, filesize($tempFileFullPath));
    }

    /**
     * 测试复印件告警.
     */
    public function testCopyWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/20141359884989.jpg');
        $config = new Config();
        $config->setCopyWarn(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->nationalEmblemSideOcr($filePath, $config);
        $this->assertTrue($result->getExtra()['WarnInfos']['CopyWarn']);
    }

    /**
     * 测试边框和框内遮挡告警.
     */
    public function testBorderCheckWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/icon_id_03.jpg');
        $config = new Config();
        $config->setBorderCheckWarn(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->nationalEmblemSideOcr($filePath, $config);

        $this->assertTrue($result->getExtra()['WarnInfos']['BorderCheckWarn']);
    }

    /**
     * 测试翻拍告警.
     */
    public function testReshootWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/微信图片_20191012014424-1.jpg');
        $config = new Config();
        $config->setReshootWarn(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->nationalEmblemSideOcr($filePath, $config);

        $this->assertTrue($result->getExtra()['WarnInfos']['ReshootWarn']);
    }

    /**
     * 测试PS检测告警.
     */
    public function testDetectPsWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/PS.png');
        $config = new Config();
        $config->setDetectPsWarn(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->nationalEmblemSideOcr($filePath, $config);

        $this->assertTrue($result->getExtra()['WarnInfos']['DetectPsWarn']);
    }

    /**
     * 测试身份证有效日期不合法告警.
     */
    public function testInvalidDateWarn()
    {
        $filePath = $this->getTestCaseFilePath('identityCard/nationalEmblemSide/身份证日期错误.png');
        $config = new Config();
        $config->setInvalidDateWarn(true);
        $result = $this->tencentCloud()
            ->identityCard
            ->nationalEmblemSideOcr($filePath, $config);

        $this->assertTrue($result->getExtra()['WarnInfos']['InvalidDateWarn']);
    }

    /**
     * 校验结果.
     *
     * @param IdentityCard $result
     */
    private function checkResult(IdentityCard $result)
    {
        $this->assertSame('上海市公安局徐汇分局', $result->getIssuingAuthority());
        $validStartAt = Carbon::create(2005, 10, 8, 0, 0, 0);
        $this->assertSame($validStartAt->toDateTimeString(), $result->getValidStartAt()->toDateTimeString());
        $validEndAt = Carbon::create(2025, 10, 8, 0, 0, 0);
        $this->assertSame($validEndAt->toDateTimeString(), $result->getValidEndAt()->toDateTimeString());
    }
}
