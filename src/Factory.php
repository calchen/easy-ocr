<?php

namespace Calchen\EasyOcr;

use Calchen\EasyOcr\Exception\ErrorCodes;
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
        if (!in_array($name, static::AVAILABLE_APPLICATIONS)) {
            throw new InvalidArgumentException(ErrorCodes::APPLICATION_NAME_INVALID);
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
