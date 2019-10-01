<?php

namespace Calchen\EasyOcr\Test;

use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Factory;
use Exception;

class FactoryTest extends TestCase
{
    public function testInvalidArgument()
    {
        try {
            Factory::unknown();
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
