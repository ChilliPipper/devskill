<?php

declare(strict_types=1);

namespace App\Tests\Base;

use App\CompositeEncodingAlgorithm;
use App\EncodingAlgorithm;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class CompositeEncodingAlgorithmTest extends TestCase
{
    private ?Prophet $prophet = null;

    protected function setup(): void
    {
        $this->prophet = new Prophet;
    }

    public function testComposedAlgorithmsAreCalled(): void
    {
        $algorithmA = $this->prophet->prophesize(EncodingAlgorithm::class);
        $algorithmB = $this->prophet->prophesize(EncodingAlgorithm::class);

        $algorithmA->encode("plain text")->willReturn("encoded text");
        $algorithmB->encode("encoded text")->willReturn("text encoded twice");

        $algorithm = new CompositeEncodingAlgorithm();
        $algorithm->add($algorithmA->reveal());
        $algorithm->add($algorithmB->reveal());

        $this->assertSame("text encoded twice", $algorithm->encode("plain text"));
    }

    public function testEncodingWithEmptyAlgorithmsList(): void
    {
        $this->expectException(\Exception::class);
        $algorithm = new CompositeEncodingAlgorithm();

        $encoded = $algorithm->encode('lorem ipsum');

        // Encoding with empty list of algorithms shouldn't return string, but null, false or throws exception.
        if (is_string($encoded)) {
            $this->fail('Exception should be thrown');
        } else {
            $this->assertTrue(is_null($encoded) || $encoded === false);
        }
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }
}
