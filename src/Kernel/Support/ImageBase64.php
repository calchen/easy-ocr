<?php

namespace Calchen\EasyOcr\Kernel\Support;

use SplFileInfo;

class ImageBase64
{
    /**
     * @param $image SplFileInfo|string
     *
     * @return string
     * @throws \Throwable
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
            // todo
            new \Exception('不是一个图片文件。');
        }

        return 'data:'.$mime.';base64,'.base64_encode($data);
    }

    /**
     * @param $data
     *
     * @return resource
     * @throws \Throwable
     */
    public static function decode($data)
    {
        if (self::isInvalid($data)) {
            // todo
            new \Exception('不是合法的图片 Base64 字符串。');
        }

        $image = base64_decode(explode(';base64,', $data)[1]);

        /** @var resource $temp */
        $temp = tmpfile();
        // tempnam()
        fwrite($temp, $image);
        fseek($temp, 0);
        // fflush($temp);
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
