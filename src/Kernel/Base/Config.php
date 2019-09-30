<?php

namespace Calchen\EasyOcr\Kernel\Base;

use ReflectionObject;
use ReflectionProperty;

abstract class Config
{
    /**
     * 输出成接口所需参数数组形式
     *
     * @param callable|null $callback
     *
     * @return array
     */
    public function toArray(callable $callback = null): array
    {
        $result = [];

        $reflect = new ReflectionObject($this);
        foreach ($reflect->getProperties(ReflectionProperty::IS_PROTECTED) as $prop) {
            $propName = $prop->getName();
            if (!is_null($callback)) {
                $result = $callback($result, $propName);
                continue;
            }

            // 默认处理方法
            $result[$propName] = $this->$propName;
        }

        return $result;
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}