<?php

namespace Calchen\EasyOcr\TencentCloud\BusinessLicense;

use Calchen\EasyOcr\Kernel\Base\Config;
use Calchen\EasyOcr\Kernel\Support\ImageBase64;
use Calchen\EasyOcr\Kernel\Support\Str;
use Calchen\EasyOcr\Models\BusinessLicense;
use TencentCloud\Ocr\V20181119\Models\BizLicenseOCRRequest;

class BusinessLicenseClient extends \Calchen\EasyOcr\Kernel\Contract\BusinessLicenseClient
{
    /**
     * 本接口支持快速精准识别营业执照上的字段
     * 包括注册号、公司名称、经营场所、主体类型、法定代表人、注册资金、组成形式、成立日期、营业期限和经营范围等字段。
     *
     * @link https://cloud.tencent.com/document/api/866/36215
     *
     * @param             $picture
     * @param Config|null $config
     *
     * @return BusinessLicense
     * @throws \Throwable
     */
    public function ocr($picture, Config $config = null): BusinessLicense
    {
        $params = [];

        // 根据文档：图片的 ImageUrl、ImageBase64 必须提供一个，如果都提供，只使用 ImageUrl。
        if (Str::startsWith($picture, 'http')) {
            $params['ImageUrl'] = $picture;
        } else {
            $params['ImageBase64'] = ImageBase64::encode($picture);
        }

        $req = new BizLicenseOCRRequest();
        $req->fromJsonString(json_encode($params));
        $resp = static::getTencentCloudClient()->BizLicenseOCR($req);

        return new BusinessLicense($resp);
    }
}
