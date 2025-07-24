<?php

declare(strict_types=1);

namespace App\Tests\Base;

use App\SubstitutionEncodingAlgorithm;
use PHPUnit\Framework\TestCase;

class SubstitutionEncodingAlgorithmTest extends TestCase
{
    /**
     * @dataProvider getValidSubstitutions
     */
    public function testValidSubstitutions(array $substitutions, string $text, string $encoded): void
    {
        $algorithm = new SubstitutionEncodingAlgorithm($substitutions);

        $this->assertEquals($encoded, $algorithm->encode($text));
    }

    /**
     * Data provider for {@link SubstitutionEncodingAlgorithmTest::testValidSubstitutions()}
     */
    public function getValidSubstitutions(): array
    {
        return [
            [['ab'], 'aabbcc', 'bbaacc'],
            [['ab', 'cd'], 'adam', 'bcbm'],
            [['ab', 'cd'], 'AdAm', 'BcBm'],
            [['ga', 'de', 'ry', 'po', 'lu', 'ki'], 'lorem ipsum dolor', 'upydm koslm epupy'],
        ];
    }

    /**
     * @dataProvider getIllegalSubstitutions
     */
    public function testIllegalSubstitutions(array $substitutions): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $algorithm = new SubstitutionEncodingAlgorithm($substitutions);

        $algorithm->encode('');

        $this->fail('Exception should be thrown');
    }

    public function getIllegalSubstitutions(): array
    {
        return [
            [['gg']],
            [['ga', 'gb']],
            [['g']],
        ];
    }
}
