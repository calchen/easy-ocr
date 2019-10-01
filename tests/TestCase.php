<?php

namespace Calchen\EasyOcr\Test;

use Calchen\EasyOcr\Factory;
use Calchen\EasyOcr\Kernel\ServiceContainer;
use Calchen\EasyOcr\Kernel\Support\Str;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * 获取配置好的 tencentCloud 对象
     *
     * @return ServiceContainer
     */
    protected function tencentCloud(): ServiceContainer
    {
        return Factory::tencentCloud([
            'secret_id' => $_ENV['TENCENT_CLOUD_SECRET_ID'],
            'secret_key' => $_ENV['TENCENT_CLOUD_SECRET_KEY'],
            'endpoint' => $_ENV['TENCENT_CLOUD_OCR_ENDPOINT'],
            'region' => $_ENV['TENCENT_CLOUD_OCR_REGION'],
            'app_id' => $_ENV['TENCENT_CLOUD_APP_ID'],
        ]);
    }

    /**
     * 获取临时文件的路径.
     *
     * @param string $dir
     * @param string $namespace
     * @param string $class
     * @param string $function
     *
     * @return string
     * @throws \Exception
     */
    protected function generateTempImageFilePath(string $dir, string $namespace, string $class, string $function)
    {
        $tempFilePath = implode('/', [
            $dir.'/temp',
            Str::replaceFirst($namespace.'\\', '', $class),
            $function,
        ]);
        // 如果文件夹不存在，需要创建
        ! file_exists($tempFilePath) && mkdir($tempFilePath, 0777, true);

        return "$tempFilePath/".Str::random().'.jpg';
    }

    /**
     * 获取需要测试的文件的路径.
     *
     * @param string $fileName
     *
     * @return string
     */
    protected function getTestCaseFilePath(string $fileName): string
    {
        return  __DIR__."/examples/{$fileName}";
    }
}
