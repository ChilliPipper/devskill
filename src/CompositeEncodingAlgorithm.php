<?php

declare(strict_types=1);

namespace App;

class CompositeEncodingAlgorithm implements EncodingAlgorithm
{
    /**
     * @var EncodingAlgorithm[]
     */
    private array $algorithms;

    public function __construct()
    {
        $this->algorithms = [];
    }

    /**
     * @param EncodingAlgorithm $algorithm
     */
    public function add(EncodingAlgorithm $algorithm): void
    {
        $this->algorithms[] = $algorithm;
    }

    /**
     * Encodes text using multiple Encoders (in orders encoders were added)
     *
     * @param string $text
     * @return string
     */
    public function encode(string $text): string
    {
        if (empty($this->algorithms)) {
            throw new \Exception('No algorithms added to composite encoder');
        }

        $result = $text;
        
        foreach ($this->algorithms as $algorithm) {
            $result = $algorithm->encode($result);
        }

        return $result;
    }
}
