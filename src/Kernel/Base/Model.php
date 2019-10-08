<?php

namespace Calchen\EasyOcr\Kernel\Base;

abstract class Model
{
    /**
     * @var string 图片文件保存的磁盘
     */
    private $scheme;

    /**
     * @var string 图片文件在磁盘上的路径
     */
    private $path;

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function setUri(string $scheme, string $path = null): self
    {
        if (is_null($path)) {
            list($this->scheme, $this->path) = explode('::', $scheme);
        } else {
            $this->scheme = $scheme;
            $this->path = $path;
        }

        return $this;
    }

    public function getUri()
    {
        return is_null($this->scheme) || is_null($this->path) ? null : "{$this->scheme}::{$this->path}";
    }

    abstract public function toArray();

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
