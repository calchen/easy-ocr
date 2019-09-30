<?php

namespace Calchen\EasyOcr\Kernel;

use Calchen\EasyOcr\Kernel\Contract\BusinessLicenseClient;
use Calchen\EasyOcr\Kernel\Contract\IdentityCardClient;
use Pimple\Container;

/**
 * Class Application
 * @package Calchen\EasyOcr\TencentCloud
 *
 * @property IdentityCardClient    identityCard
 * @property BusinessLicenseClient businessLicense
 */
class ServiceContainer extends Container
{
    /**
     * @var array
     */
    protected $providers = [];

    public function __construct(array $values = [])
    {
        $this->registerProviders($this->providers);

        parent::__construct($values);
    }


    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }
}