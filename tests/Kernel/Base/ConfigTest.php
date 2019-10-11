<?php

namespace Calchen\EasyOcr\Test\Kernel\Base;

use Calchen\EasyOcr\Test\TestCase;

class ConfigTest extends TestCase
{
    public function testToString()
    {
        $config = new DemoConfig();

        $this->assertSame((string) $config, '{"userName":null,"password":"password"}');
    }

    public function testJsonEncode()
    {
        $config = new DemoConfig();

        $this->assertSame(json_encode($config), '{"userName":null,"password":"password"}');
    }
}
