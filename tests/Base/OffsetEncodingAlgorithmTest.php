<?php

declare(strict_types=1);

namespace App\Tests\Base;

use App\OffsetEncodingAlgorithm;
use PHPUnit\Framework\TestCase;

class OffsetEncodingAlgorithmTest extends TestCase
{
    /**
     * @dataProvider getTexts
     */
    public function testValidEncoding(int $offset, string $text, string $encoded): void
    {
        $algorithm = new OffsetEncodingAlgorithm($offset);

        $this->assertEquals($encoded, $algorithm->encode($text));
    }

    /**
     * @dataProvider getIllegalOffsets
     */
    public function testIllegalParameters(int $offset): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new OffsetEncodingAlgorithm($offset);

        $this->fail('Exception should be thrown');
    }

    public function getTexts(): array
    {
        return [
            [0, '', ''],
            [1, '', ''],
            [1, 'a', 'b'],
            [0, 'abc def.', 'abc def.'],
            [1, 'abc def.', 'bcd efg.'],
            [2, 'z', 'B'],
            [1, 'Z', 'a'],
            [26, 'abc def.', 'ABC DEF.'],
            [26, 'ABC DEF.', 'abc def.'],
        ];
    }

    /**
     * Data provider for {@link OffsetEncodingAlgorithmTest::testIllegalParameters()}
     */
    public function getIllegalOffsets(): array
    {
        return [
            [-1],
        ];
    }
}
