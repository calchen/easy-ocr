<?php

namespace Calchen\EasyOcr\TencentCloud\BusinessLicense;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['businessLicense'] = function ($app) {
            return new BusinessLicenseClient($app);
        };
    }
}
