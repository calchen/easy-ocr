<?php

namespace Calchen\EasyOcr\Models;

use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Kernel\Base\Model;
use Calchen\EasyOcr\Kernel\Support\Str;
use Carbon\Carbon;
use TencentCloud\Ocr\V20181119\Models\BizLicenseOCRResponse;

class BusinessLicense extends Model
{
    /**
     * @var string 注册号
     */
    private $code;

    /**
     * @var string 公司名称
     */
    private $name;

    /**
     * @var string 主体类型
     */
    private $type;

    /**
     * @var string 地址
     */
    private $address;

    /**
     * @var string 法人
     */
    private $legalPerson;

    /**
     * @var string 注册资本
     */
    private $registeredCapital;

    /**
     * @var Carbon 成立日期
     */
    private $registeredAt;

    /**
     * 营业期限开始日期
     *
     * @var Carbon
     */
    private $validStartAt;

    /**
     * 营业期限结束日期
     *
     * @var Carbon
     */
    private $validEndAt;

    /**
     * 营业期限长期有效.
     *
     * @var bool
     */
    private $isLongTermValid = false;

    /**
     * @var string 经营范围
     */
    private $scope;

    /**
     * @var string 组成形式
     */
    private $organizationType;

    /**
     * BusinessLicense constructor.
     *
     * @param $data
     *
     * @throws InvalidArgumentException
     */
    public function __construct($data)
    {
        if ($data instanceof BizLicenseOCRResponse) {
            $this->createFromTencentCloud($data);
        } else {
            throw new InvalidArgumentException();
        }
    }

    public function createFromTencentCloud(BizLicenseOCRResponse $response)
    {
        $this->code = $response->RegNum;
        $this->name = $response->Name;
        $this->registeredCapital = $response->Capital;
        $this->legalPerson = $response->Person;
        $this->address = $response->Address;
        $this->scope = $response->Business;
        $this->type = $response->Type;

        // 处理营业期限
        $period = $response->Period;
        preg_match_all('/\d{4}年\d{1,2}月\d{1,2}日/', $period, $match);
        @list($this->validStartAt, $this->validEndAt) = array_map(function ($date) {
            return Carbon::createFromFormat('Y年m月d日', $date)->startOfDay();
        }, $match[0]);
        if (Str::endsWith($period, '长期')) {
            $this->isLongTermValid = true;
        }

        $this->organizationType = $response->ComposingForm;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
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
    public function getType()
    {
        return $this->type;
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
    public function getLegalPerson()
    {
        return $this->legalPerson;
    }

    /**
     * @return string
     */
    public function getRegisteredCapital()
    {
        return $this->registeredCapital;
    }

    /**
     * @return Carbon
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
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
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getOrganizationType()
    {
        return $this->organizationType;
    }

    public function toArray()
    {
        return [
            'uri' => $this->getURI(),
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'address' => $this->getAddress(),
            'legal_person' => $this->getLegalPerson(),
            'registered_capital' => $this->getRegisteredCapital(),
            'registered_at' => $this->getRegisteredAt() ? $this->getRegisteredAt()->getTimestamp() : null,
            'valid_start_at' => $this->getValidStartAt() ? $this->getValidStartAt()->getTimestamp() : null,
            'valid_end_at' => $this->getValidEndAt() ? $this->getValidEndAt()->getTimestamp() : null,
            'is_long_term_valid' => $this->getIsLongTermValid(),
            'scope' => $this->getScope(),
            'organization_type' => $this->getOrganizationType(),
        ];
    }
}
