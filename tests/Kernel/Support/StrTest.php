<?php

namespace Calchen\EasyOcr\Test\Kernel\Support;

use Calchen\EasyOcr\Kernel\Support\Str;
use Calchen\EasyOcr\Test\TestCase;

class StrTest extends TestCase
{
    public function testEndsWith()
    {
        $this->assertFalse(Str::endsWith('easy-ocr', 'easy'));
    }

    public function testReplaceFirst()
    {
        $this->assertSame('easy-ocr', Str::replaceFirst('', 'ocr', 'easy-ocr'));
        $this->assertSame('easy-ocr', Str::replaceFirst('OCR', 'ocr', 'easy-ocr'));
    }
}