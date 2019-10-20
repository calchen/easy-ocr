<?php

namespace Calchen\EasyOcr\Kernel\Support;

use Calchen\EasyOcr\Exception\ErrorCodes;
use Calchen\EasyOcr\Exception\Exception;
use Calchen\EasyOcr\Exception\InvalidArgumentException;
use SplFileInfo;
use Throwable;

class ImageBase64
{
    /**
     * @param $image SplFileInfo|string
     *
     * @return string
     * @throws Throwable
     */
    public static function encode($image)
    {
        if (static::isValid($image)) {
            return $image;
        } elseif ($image instanceof SplFileInfo) {
            $mime = mime_content_type($image->getRealPath());
            $data = file_get_contents($image->getRealPath());
        } elseif (static::isFilepath($image)) {
            $mime = mime_content_type($image);
            $data = file_get_contents($image);
        } else {
            $temp = tmpfile();
            fwrite($temp, $image);
            $mime = mime_content_type($temp);
            $data = $image;
        }

        if (Str::startsWith($mime, 'image/')) {
            new Exception(ErrorCodes::FILE_TYPE_INVALID_NOT_IMAGE);
        }

        return "data:{$mime};base64,".base64_encode($data);
    }

    /**
     * @param $data
     *
     * @return resource
     * @throws Throwable
     */
    public static function decode($data)
    {
        if (self::isInvalid($data)) {
            throw new InvalidArgumentException(ErrorCodes::IMAGE_BASE64_STRING_INVALID);
        }

        $image = base64_decode(explode(';base64,', $data)[1]);

        /** @var resource $temp */
        $temp = tmpfile();
        fwrite($temp, $image);
        // 回到文件开头否则许多操作无法进行
        fseek($temp, 0);

        return $temp;
    }

    public static function isValid($haystack)
    {
        return Str::startsWith($haystack, 'data:image/');
    }

    public static function isInvalid($haystack)
    {
        return ! static::isValid($haystack);
    }

    /**
     * 判断文件路径是否合法.
     *
     * @param $path
     *
     * @return false|int
     */
    public static function isFilepath($path)
    {
        return preg_match('/^[^*?"<>|:]*$/', trim($path));
    }
}
