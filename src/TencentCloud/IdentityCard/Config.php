<?php

namespace Calchen\EasyOcr\TencentCloud\IdentityCard;

/**
 * 身份证识别 Config
 *
 * @link https://cloud.tencent.com/document/api/866/33524#2.-.E8.BE.93.E5.85.A5.E5.8F.82.E6.95.B0
 *
 * Class Config
 * @package Calchen\EasyOcr\TencentCloud\IdentityCard\Model
 */
class Config extends \Calchen\EasyOcr\Kernel\Base\Config
{
    /**
     * @var bool 是否进行身份证照片裁剪
     */
    protected $cropIdCard = false;

    /**
     * @var bool 是否进行人像照片裁剪
     */
    protected $cropPortrait = false;

    /**
     * @var bool 复印件告警
     */
    protected $copyWarn = false;

    /**
     * @var bool 边框和框内遮挡告警
     */
    protected $borderCheckWarn = false;

    /**
     * @var bool 翻拍告警
     */
    protected $reshootWarn = false;

    /**
     * @var bool PS检测告警
     */
    protected $detectPsWarn = false;

    /**
     * @var bool 临时身份证告警
     */
    protected $tempIdWarn = false;

    /**
     * @var bool 身份证有效日期不合法告警
     */
    protected $invalidDateWarn = false;

    /**
     * @param bool $cropIdCard
     *
     * @return Config
     */
    public function setCropIdCard(bool $cropIdCard): self
    {
        $this->cropIdCard = $cropIdCard;

        return $this;
    }

    /**
     * @param bool $cropPortrait
     *
     * @return Config
     */
    public function setCropPortrait(bool $cropPortrait): self
    {
        $this->cropPortrait = $cropPortrait;

        return $this;
    }

    /**
     * @param bool $copyWarn
     *
     * @return Config
     */
    public function setCopyWarn(bool $copyWarn): self
    {
        $this->copyWarn = $copyWarn;

        return $this;
    }

    /**
     * @param bool $borderCheckWarn
     *
     * @return Config
     */
    public function setBorderCheckWarn(bool $borderCheckWarn): self
    {
        $this->borderCheckWarn = $borderCheckWarn;

        return $this;
    }

    /**
     * @param bool $reshootWarn
     *
     * @return Config
     */
    public function setReshootWarn(bool $reshootWarn): self
    {
        $this->reshootWarn = $reshootWarn;

        return $this;
    }

    /**
     * @param bool $detectPsWarn
     *
     * @return Config
     */
    public function setDetectPsWarn(bool $detectPsWarn): self
    {
        $this->detectPsWarn = $detectPsWarn;

        return $this;
    }

    /**
     * @param bool $tempIdWarn
     *
     * @return Config
     */
    public function setTempIdWarn(bool $tempIdWarn): self
    {
        $this->tempIdWarn = $tempIdWarn;

        return $this;
    }

    /**
     * @param bool $invalidDateWarn
     *
     * @return Config
     */
    public function setInvalidDateWarn(bool $invalidDateWarn): self
    {
        $this->invalidDateWarn = $invalidDateWarn;

        return $this;
    }

    /**
     * 输出成接口所需参数数组形式
     *
     * @param callable|null $callback
     *
     * @return array
     */
    public function toArray(callable $callback = null): array
    {
        return parent::toArray($callback ?: function ($result, $propName) {
            $result[ucwords($propName)] = $this->$propName;

            return $result;
        });
    }
}