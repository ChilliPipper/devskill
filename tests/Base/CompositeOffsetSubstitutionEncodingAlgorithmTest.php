<?php

declare(strict_types=1);

namespace App\Tests\Base;

use App\CompositeEncodingAlgorithm;
use App\OffsetEncodingAlgorithm;
use App\SubstitutionEncodingAlgorithm;
use PHPUnit\Framework\TestCase;

class CompositeOffsetSubstitutionEncodingAlgorithmTest extends TestCase
{
    /**
     * @dataProvider getTexts
     */
    public function testValidEncoding(int $offset, string $text, string $encoded): void
    {
        $algorithm = new CompositeEncodingAlgorithm();

        $algorithm->add(new OffsetEncodingAlgorithm($offset));
        $algorithm->add(new SubstitutionEncodingAlgorithm(['ga', 'de', 'ry', 'po', 'lu', 'ki']));

        $this->assertEquals($encoded, $algorithm->encode($text));
    }

    public function getTexts(): array
    {
        return [
            [0, '', ''],
            [0, 'abc', 'gbc'],
            [1, 'abc', 'bce'],
            [1, 'abc def, ghi.', 'bce dfa, hkj.'],
            [26, 'abc def.', 'GBC EDF.'],
            [26, 'ABC DEF.', 'gbc edf.'],
        ];
    }

    /**
     * @dataProvider getReversedTexts
     */
    public function testReverseOrder(int $offset, string $text, string $encoded): void
    {
        $algorithm = new CompositeEncodingAlgorithm();

        $algorithm->add(new SubstitutionEncodingAlgorithm(['ga', 'de', 'ry', 'po', 'lu', 'ki']));
        $algorithm->add(new OffsetEncodingAlgorithm($offset));

        $this->assertEquals($encoded, $algorithm->encode($text));
    }

    public function getReversedTexts(): array
    {
        return [
            [0, 'abc', 'gbc'],
            [1, 'abc', 'hcd'],
            [1, 'abc def, ghi.', 'hcd feg, bil.'],
        ];
    }
}
