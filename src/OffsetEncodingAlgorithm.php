<?php

declare(strict_types=1);

namespace App;

class OffsetEncodingAlgorithm implements EncodingAlgorithm
{
    /**
     * Lookup string
     */
    public const CHARACTERS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private int $offset;

    public function __construct(int $offset = 13)
    {
        if ($offset < 0) {
            throw new \InvalidArgumentException('Offset must be non-negative');
        }
        $this->offset = $offset;
    }

    /**
     * Encodes text by shifting each character (existing in the lookup string) by an offset (provided in the constructor)
     * Examples:
     *      offset = 1, input = "a", output = "b"
     *      offset = 2, input = "z", output = "B"
     *      offset = 1, input = "Z", output = "a"
     */
    public function encode(string $text): string
    {
        if ($this->offset === 0) {
            return $text;
        }

        $result = '';
        $charactersLength = strlen(self::CHARACTERS);
        
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $position = strpos(self::CHARACTERS, $char);
            
            if ($position === false) {
                // Character not in lookup string, keep as is
                $result .= $char;
            } else {
                // Shift character by offset, wrapping around
                $newPosition = ($position + $this->offset) % $charactersLength;
                $result .= self::CHARACTERS[$newPosition];
            }
        }

        return $result;
    }
}
