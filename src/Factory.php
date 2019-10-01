<?php

namespace Calchen\EasyOcr;

use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Kernel\ServiceContainer;

/**
 * Class Factory.
 *
 * @method static ServiceContainer app(array $config = [])
 * @method static ServiceContainer tencentCloud(array $config = [])
 */
class Factory
{
    const AVAILABLE_APPLICATIONS = [
        'tencentCloud',
    ];

    /**
     * @param       $name
     * @param array $config
     *
     * @return ServiceContainer
     * @throws \Throwable
     */
    public static function make($name, array $config = [])
    {
        if (! in_array($name, static::AVAILABLE_APPLICATIONS)) {
            throw new InvalidArgumentException('Application 参数错误。仅数组 '.self::class.'::AVAILABLE_APPLICATIONS 中的值可用。');
        }

        $application = '\\Calchen\\EasyOcr\\'.ucwords($name).'\\Application';

        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws \Throwable
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
