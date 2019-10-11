<?php

namespace Calchen\EasyOcr\Models;

use Calchen\EasyOcr\Exception\Exception;
use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Kernel\Base\Model;
use Calchen\EasyOcr\Kernel\Support\Str;
use Carbon\Carbon;
use TencentCloud\Ocr\V20181119\Models\IDCardOCRResponse;

class IdentityCard extends Model
{
    const GENDERS = [
        0 => 'male',
        1 => 'female',
        2 => 'unknown',
    ];

    /**
     * @var string 姓名（正面）
     */
    private $name;

    /**
     * @var string 性别（正面）
     */
    private $gender;

    /**
     * @var string 民族（正面）
     */
    private $nation;

    /**
     * @var Carbon 出生日期 （正面）
     */
    private $birthday;

    /**
     * @var string 住址（正面）
     */
    private $address;

    /**
     * @var string 公民身份证号码（正面）
     */
    private $number;

    /**
     * @var string 签发机关（反面）
     */
    private $issuingAuthority;

    /**
     * @var Carbon 有效期开始日期（反面）
     */
    private $validStartAt;

    /**
     * @var Carbon 有效期结束日期（反面）
     */
    private $validEndAt;

    /**
     * 长期有效.
     *
     * @var bool
     */
    private $isLongTermValid = false;

    /**
     * @var bool 是否为个人信息面
     */
    private $isPersonalInfoSide;

    /**
     * @var array 证面以外的数据，根据不同的渠道及不用的入参可能存在额外的数据，全部在这里返回
     */
    private $extra = [];

    /**
     * IdentityCard constructor.
     *
     * @param $data
     *
     * @throws Exception
     */
    public function __construct($data)
    {
        if ($data instanceof IDCardOCRResponse) {
            $this->createFromTencentCloud($data);
        } else {
            throw new InvalidArgumentException();
        }
    }

    /**
     * 根据腾讯云接口返回进行实例化.
     *
     * @param IDCardOCRResponse $response
     *
     * @throws Exception
     */
    private function createFromTencentCloud(IDCardOCRResponse $response)
    {
        if (
            $response->Sex != '' ||
            $response->Name !== '' ||
            $response->IdNum != '' ||
            $response->Birth != '' ||
            $response->Nation != '' ||
            $response->Address != ''
        ) {
            $this->isPersonalInfoSide = true;

            $this->name = $response->Name;
            $gender = $response->Sex;
            $this->gender = $gender === '男' ? 0 : ($gender === '女' ? 1 : 2);
            $this->nation = $response->Nation;
            $this->birthday = Carbon::createFromFormat('Y/m/d', $response->Birth)->startOfDay();
            $this->address = $response->Address;
            $this->number = $response->IdNum;
        } elseif ($response->Authority != '' || $response->ValidDate != '') {
            $this->isPersonalInfoSide = false;

            $this->issuingAuthority = $response->Authority;
            $validDate = explode('-', $response->ValidDate);
            $this->validStartAt = Carbon::createFromFormat('Y.m.d', $validDate[0])->startOfDay();
            if ($validDate[1] == '长期') {
                $this->isLongTermValid = true;
            } else {
                $this->validEndAt = Carbon::createFromFormat('Y.m.d', $validDate[1])->startOfDay();
            }
        } else {
            // 腾讯云 OCR 无法识别临时身份证，会报错
            // 但是如果识别了临时身份证且设置了 Config 参数中的 TempIdWarn = true，就不会报错但返回的其他字段均为空字符串
            // -9104 临时身份证告警
            if (Str::contains($response->AdvancedInfo, '-9104')) {
                // todo
                throw new Exception('无法识别临时身份证');
            }

            // 在传入 Config 的时候，即使 OCR 识别失败也不会抛错，会返回所有字段为空字符串
            // todo
            throw new Exception('Ocr 识别失败');
        }

        $advancedInfo = json_decode($response->AdvancedInfo, true);

        // 处理 IdCard 字段，由 CropIdCard 控制
        if (isset($advancedInfo['IdCard'])) {
            // 这里经过测试，返回的图片是 jpeg 格式
            $this->extra['IdCard'] = 'data:image/jpeg;base64,'.$advancedInfo['IdCard'];
        }

        // 处理 Portrait 字段，由 CropPortrait 控制
        if (isset($advancedInfo['Portrait'])) {
            // 这里经过测试，返回的图片是 jpeg 格式
            $this->extra['Portrait'] = 'data:image/jpeg;base64,'.$advancedInfo['Portrait'];
        }

        // 处理 WarnInfos 字段
        if (isset($advancedInfo['WarnInfos']) && is_array($advancedInfo['WarnInfos'])) {
            $this->extra['WarnInfos'] = [];
            foreach ($advancedInfo['WarnInfos'] as $info) {
                /*
                 * -9100 身份证有效日期不合法告警，
                 * -9101 身份证边框不完整告警，
                 * -9102 身份证复印件告警，
                 * -9103 身份证翻拍告警，
                 * -9104 临时身份证告警，
                 * -9105 身份证框内遮挡告警，
                 * -9106 身份证 PS 告警。
                 */
                switch ($info) {
                    case -9100:
                        $this->extra['WarnInfos']['InvalidDateWarn'] = true;
                        break;
                    case -9101:
                    case -9105:
                        // 根据文档 BorderCheckWarn 为边框和框内遮挡告警，故此认为身份证边框不完整告警与身份证框内遮挡告警皆为此参数控制
                        $this->extra['WarnInfos']['BorderCheckWarn'] = true;
                        break;
                    case -9102:
                        $this->extra['WarnInfos']['CopyWarn'] = true;
                        break;
                    case -9103:
                        $this->extra['WarnInfos']['ReshootWarn'] = true;
                        break;
                    // case -9104:
                    //     $this->extra['WarnInfos']['TempIdWarn'] = true;
                    //     break;
                    case -9106:
                        $this->extra['WarnInfos']['DetectPsWarn'] = true;
                        break;
                    default:
                        // todo 需要记录日志
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return self::GENDERS[$this->gender] ?? null;
    }

    /**
     * @return string
     */
    public function getNation()
    {
        return $this->nation;
    }

    /**
     * @return Carbon
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getIssuingAuthority()
    {
        return $this->issuingAuthority;
    }

    /**
     * @return Carbon
     */
    public function getValidStartAt()
    {
        return $this->validStartAt;
    }

    /**
     * @return Carbon
     */
    public function getValidEndAt()
    {
        return $this->validEndAt;
    }

    /**
     * @return bool
     */
    public function getIsLongTermValid(): bool
    {
        return $this->isLongTermValid;
    }

    /**
     * @return bool
     */
    public function isPersonalInfoSide()
    {
        return $this->isPersonalInfoSide;
    }

    /**
     * @return bool
     */
    public function isNationalEmblemSide(): bool
    {
        return ! $this->isPersonalInfoSide;
    }

    /**
     * @return array
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    public function toArray()
    {
        return [
            'uri' => $this->getURI(),
            'name' => $this->getName(),
            'gender' => $this->getGender(),
            'nation' => $this->getNation(),
            'birthday' => $this->getBirthday() ? $this->getBirthday()->getTimestamp() : null,
            'address' => $this->getAddress(),
            'number' => $this->getNumber(),
            'issuing_authority' => $this->getIssuingAuthority(),
            'valid_date' => [
                'start_at' => $this->getValidStartAt() ? $this->getValidStartAt()->getTimestamp() : null,
                'end_at' => $this->getValidEndAt() ? $this->getValidEndAt()->getTimestamp() : null,
                'is_long_term_valid' => $this->getIsLongTermValid(),
            ],
            'is_personal_info_side' => $this->isPersonalInfoSide(),
            'is_national_emblem_side' => $this->isNationalEmblemSide(),
            'extra' => $this->getExtra(),
        ];
    }
}
