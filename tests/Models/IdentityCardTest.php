<?php

namespace Calchen\EasyOcr\Test\Models;

use Calchen\EasyOcr\Exception\InvalidArgumentException;
use Calchen\EasyOcr\Models\IdentityCard;
use Calchen\EasyOcr\Test\TestCase;
use Exception;

class IdentityCardTest extends TestCase
{
    /**
     * 测试入参不正确.
     *
     * @throws Exception
     */
    public function testInvalidArgument()
    {
        try {
            new IdentityCard([]);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    /**
     * 测试转数组.
     */
    public function testToArray()
    {
        $filePath = $this->getTestCaseFilePath('icon_id_01.jpg');
        $result = $this->tencentCloud()
            ->identityCard
            ->personalInfoSideOcr($filePath);
        $this->assertTrue(is_array($result->toArray()));
    }
}
