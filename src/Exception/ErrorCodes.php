<?php

namespace Calchen\EasyOcr\Exception;

use Calchen\EasyOcr\Factory;
use Calchen\EasyOcr\Kernel\Contract\IdentityCardClient;

class ErrorCodes
{
    // 1-999 用于通用基本错误
    //

    // 1001-9999 用于基础业务错误
    const IDENTITY_CARD_SIDE_ARGUMENT_INVALID = 1001;
    const IMAGE_BASE64_STRING_INVALID = 1002;
    const FILE_TYPE_INVALID_NOT_IMAGE = 1003;
    const BUSINESS_LICENSE_CREATE_FAILED_UNKNOWN_DATA = 1004;
    const IDENTITY_CARD_CREATE_FAILED_UNKNOWN_DATA = 1005;
    const APPLICATION_NAME_INVALID = 1006;

    // 10001-99999 用于各个服务商业务错误，2位服务商标记+3位错误码，腾讯云：10，阿里云：11
    const TENCENT_CLOUD_IDENTITY_CARD_IS_TEMPORARY = 10001;
    const TENCENT_CLOUD_IDENTITY_CARD_OCR_EMPTY_DATA = 10002;
    const TENCENT_CLOUD_API_EXCEPTION = 10003;

    const MESSAGES = [
        self::IDENTITY_CARD_SIDE_ARGUMENT_INVALID => '身份证正反面参数错误。仅数组 '.IdentityCardClient::class.'::CARD_SIDES 中的值可用。',
        self::IMAGE_BASE64_STRING_INVALID => '不是合法的图片 Base64 字符串。',
        self::FILE_TYPE_INVALID_NOT_IMAGE => '不是一个图片文件。',
        self::BUSINESS_LICENSE_CREATE_FAILED_UNKNOWN_DATA => '营业执照对象创建失败，未知数据',
        self::IDENTITY_CARD_CREATE_FAILED_UNKNOWN_DATA => '身份证对象创建失败，未知数据',
        self::APPLICATION_NAME_INVALID => 'Application 参数错误。仅数组 '.Factory::class.'::AVAILABLE_APPLICATIONS 中的值可用。',

        self::TENCENT_CLOUD_IDENTITY_CARD_IS_TEMPORARY => '无法识别临时身份证',
        self::TENCENT_CLOUD_IDENTITY_CARD_OCR_EMPTY_DATA => '服务调用成功但识别失败',
        self::TENCENT_CLOUD_API_EXCEPTION => '腾讯云服务调用失败',
    ];
}
