<?php

namespace Tests\Unit\Helpers;

use Codeception\Test\Unit;
use PhoneHelper;

class PhoneHelperTest extends Unit
{
    /**
     * @dataProvider providerNormalize
     *
     * @param string $phone
     * @param string $expectedResult
     */
    public function testNormalizePhone($phone, $expectedResult)
    {
        $this->assertEquals($expectedResult, PhoneHelper::normalizePhone($phone));
    }

    public function providerNormalize(): array
    {
        return [
            [
                'phone' => '+7(917)555-44-33',
                'expectedResult' => '79175554433',
            ],
            [
                'phone' => '8(917)555-44-33',
                'expectedResult' => '79175554433',
            ],
            [
                'phone' => '79175554433',
                'expectedResult' => '79175554433',
            ],
        ];
    }
}
