<?php


namespace Calchen\EasyOcr\Kernel\Contract;

use Calchen\EasyOcr\Kernel\Base\Client;
use Calchen\EasyOcr\Kernel\Base\Config;
use Calchen\EasyOcr\Models\BusinessLicense;

abstract class BusinessLicenseClient extends Client
{
    /**
     * @param             $picture
     * @param Config|null $config
     *
     * @return BusinessLicense
     */
    abstract public function ocr($picture, Config $config = null): BusinessLicense;
}