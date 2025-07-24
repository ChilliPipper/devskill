<?php

declare(strict_types=1);

namespace App;

interface EncodingAlgorithm
{
    public function encode(string $text): string;
}
