<?php

namespace Calchen\EasyOcr\Test\Kernel\Base;

use Calchen\EasyOcr\Test\TestCase;

class ModelTest extends TestCase
{
    public function testUri()
    {
        $model = new DemoModel();
        $model->setUri('oss::/a/b/c/d.jpg');
        $this->assertSame($model->getScheme(), 'oss');
        $this->assertSame($model->getPath(), '/a/b/c/d.jpg');
        $this->assertSame($model->getUri(), 'oss::/a/b/c/d.jpg');
        $this->assertSame((string) $model, '[]');

        $model->setUri('oss', '/a/b/c/d.jpg');
        $this->assertSame($model->getScheme(), 'oss');
        $this->assertSame($model->getPath(), '/a/b/c/d.jpg');
        $this->assertSame($model->getUri(), 'oss::/a/b/c/d.jpg');
        $this->assertSame((string) $model, '[]');
    }
}
