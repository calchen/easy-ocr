<?php

namespace Calchen\EasyOcr\TencentCloud\IdentityCard;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['identityCard'] = function ($app) {
            return new IdentityCardClient($app);
        };
    }
}
