<?php

namespace Calchen\EasyOcr\Kernel\Base;

use Calchen\EasyOcr\Kernel\ServiceContainer;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Ocr\V20181119\OcrClient;

class Client
{
    protected $app;

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    public function getTencentCloudClient()
    {
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint($this->app->endpoint);

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);

        $cred = new Credential(
            $this->app->secret_id,
            $this->app->secret_key
        );

        return new OcrClient($cred, $this->app->region, $clientProfile);
    }
}