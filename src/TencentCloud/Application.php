<?php

namespace Calchen\EasyOcr\TencentCloud;

use Calchen\EasyOcr\Kernel\ServiceContainer;

/**
 * Class Application.
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
