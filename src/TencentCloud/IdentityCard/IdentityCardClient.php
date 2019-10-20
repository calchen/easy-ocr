<?php

namespace Calchen\EasyOcr\TencentCloud\IdentityCard;

use Calchen\EasyOcr\Exception\ErrorCodes;
use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Exception\TencentCloudException;
use Calchen\EasyOcr\Kernel\Base\Config;
use Calchen\EasyOcr\Kernel\Support\ImageBase64;
use Calchen\EasyOcr\Kernel\Support\Str;
use Calchen\EasyOcr\Models\IdentityCard;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ocr\V20181119\Models\IDCardOCRRequest;
use Throwable;

class IdentityCardClient extends \Calchen\EasyOcr\Kernel\Contract\IdentityCardClient
{
    /**
     * 接口 CardSide 字段.
     */
    const TENCENT_CLOUD_CARD_SIDES = [
        'FRONT',    // 为身份证有照片的一面（人像面）
        'BACK',     // 为身份证有国徽的一面（国徽面）
    ];

    /**
     * 本接口支持二代身份证正反面所有字段的识别，包括姓名、性别、民族、出生日期、住址、公民身份证号、签发机关、有效期限；
     * 具备身份证照片和人像照片的裁剪功能，并可进行复印件、边框和框内遮挡、翻拍、PS检测、临时身份证和身份证有效日期不合法的识别告警。
     *
     * @link https://cloud.tencent.com/document/api/866/33524
     *
     * @param        $picture
     * @param string $cardSide
     * @param Config $config
     *
     * @return IdentityCard
     * @throws InvalidArgumentException
     * @throws Throwable
     */
    public function ocr($picture, string $cardSide, Config $config = null): IdentityCard
    {
        $this->checkCardSide($cardSide);

        $params = [
            // 统一的正反面标记转成接口所需字段
            'CardSide' => static::TENCENT_CLOUD_CARD_SIDES[array_search($cardSide, static::CARD_SIDES)],
        ];

        if (! is_null($config)) {
            $params['Config'] = (string) $config;
        }

        // 根据文档：图片的 ImageUrl、ImageBase64 必须提供一个，如果都提供，只使用 ImageUrl。
        if (Str::startsWith($picture, 'http')) {
            $params['ImageUrl'] = $picture;
        } else {
            $params['ImageBase64'] = ImageBase64::encode($picture);
        }

        $req = new IDCardOCRRequest();
        $req->fromJsonString(json_encode($params));

        try {
            $resp = static::getTencentCloudClient()->IDCardOCR($req);
        } catch (TencentCloudSDKException $e) {
            throw new TencentCloudException(ErrorCodes::TENCENT_CLOUD_API_EXCEPTION, [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ], $e->getPrevious());
        }

        return new IdentityCard($resp);
    }

    /**
     * 二代身份证个人信息面所有字段的识别，包括姓名、性别、民族、出生日期、住址、公民身份证号；
     * 具备身份证照片和人像照片的裁剪功能，并可进行复印件、边框和框内遮挡、翻拍、PS检测、临时身份证和身份证有效日期不合法的识别告警。
     *
     * @param             $picture
     * @param Config|null $config
     *
     * @return IdentityCard
     * @throws InvalidArgumentException
     * @throws Throwable
     */
    public function personalInfoSideOcr($picture, Config $config = null): IdentityCard
    {
        return $this->ocr($picture, static::CARD_SIDES[0], $config);
    }

    /**
     * 二代身份证国徽面所有字段的识别，包括、签发机关、有效期限；
     * 具备身份证照片和人像照片的裁剪功能，并可进行复印件、边框和框内遮挡、翻拍、PS检测、临时身份证和身份证有效日期不合法的识别告警。
     *
     * @param             $picture
     * @param Config|null $config
     *
     * @return IdentityCard
     * @throws InvalidArgumentException
     * @throws Throwable
     */
    public function nationalEmblemSideOcr($picture, Config $config = null): IdentityCard
    {
        return $this->ocr($picture, static::CARD_SIDES[1], $config);
    }
}
