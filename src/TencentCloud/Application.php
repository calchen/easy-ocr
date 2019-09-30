<?php

namespace Calchen\EasyOcr\TencentCloud;

use Calchen\EasyOcr\Kernel\ServiceContainer;

/**
 * Class Application
 * @package Calchen\EasyOcr\TencentCloud
 *
 * @property IdentityCard\IdentityCardClient       identityCard
 * @property BusinessLicense\BusinessLicenseClient businessLicense
 */
class Application extends ServiceContainer
{
    protected $providers = [
        IdentityCard\ServiceProvider::class,
        BusinessLicense\ServiceProvider::class,
    ];
}