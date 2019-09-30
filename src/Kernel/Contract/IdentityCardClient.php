<?php

namespace Calchen\EasyOcr\Kernel\Contract;

use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Kernel\Base\Client;
use Calchen\EasyOcr\Kernel\Base\Config;
use Calchen\EasyOcr\Models\IdentityCard;

abstract class IdentityCardClient extends Client
{
    /**
     * 身份证个人信息页和国徽页
     */
    const CARD_SIDES = [
        'personal_info',
        'national_emblem',
    ];

    /**
     * @param             $picture
     * @param string      $cardSide
     * @param Config|null $config
     *
     * @return IdentityCard
     */
    abstract public function ocr($picture, string $cardSide, Config $config = null): IdentityCard;

    /**
     * @param             $picture
     * @param Config|null $config
     *
     * @return IdentityCard
     */
    abstract public function personalInfoSideOcr($picture, Config $config = null): IdentityCard;

    /**
     * @param             $picture
     * @param Config|null $config
     *
     * @return IdentityCard
     */
    abstract public function nationalEmblemSideOcr($picture, Config $config = null): IdentityCard;

    /**
     * 检查身份证正反面参数是否正确
     *
     * @param $cardSide
     *
     * @throws InvalidArgumentException
     */
    protected function checkCardSide($cardSide)
    {
        if (!in_array($cardSide, static::CARD_SIDES)) {
            // todo
            throw new InvalidArgumentException('身份证正反面参数错误。仅数组 '.static::class.'::CARD_SIDES 中的值可用。');
        }
    }
}