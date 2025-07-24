<?php

declare(strict_types=1);

namespace App;

class SubstitutionEncodingAlgorithm implements EncodingAlgorithm
{
    private array $substitutions;

    /**
     * @param array<string> $substitutions
     */
    public function __construct(array $substitutions)
    {
        $this->validateSubstitutions($substitutions);
        $this->substitutions = $substitutions;
    }

    /**
     * Encodes text by substituting character with another one provided in the pair.
     * For example pair "ab" defines all "a" chars will be replaced with "b" and all "b" chars will be replaced with "a"
     * Examples:
     *      substitutions = ["ab"], input = "aabbcc", output = "bbaacc"
     *      substitutions = ["ab", "cd"], input = "adam", output = "bcbm"
     */
    public function encode(string $text): string
    {
        $result = $text;
        
        foreach ($this->substitutions as $pair) {
            $char1 = $pair[0];
            $char2 = $pair[1];
            
            // Create a mapping for all 4 possible case combinations
            $swapMap = [
                strtolower($char1) => strtolower($char2),
                strtoupper($char1) => strtoupper($char2),
                strtolower($char2) => strtolower($char1),
                strtoupper($char2) => strtoupper($char1),
            ];
            
            // Apply swaps using placeholders to avoid double substitution
            $tempResult = '';
            for ($i = 0; $i < strlen($result); $i++) {
                $char = $result[$i];
                if (isset($swapMap[$char])) {
                    $tempResult .= $swapMap[$char];
                } else {
                    $tempResult .= $char;
                }
            }
            $result = $tempResult;
        }
        
        return $result;
    }


    private function validateSubstitutions(array $substitutions): void
    {
        $usedChars = [];
        
        foreach ($substitutions as $pair) {
            if (!is_string($pair) || strlen($pair) !== 2) {
                throw new \InvalidArgumentException('Each substitution must be a 2-character string');
            }
            
            $char1 = $pair[0];
            $char2 = $pair[1];
            
            // Check if characters are the same
            if ($char1 === $char2) {
                throw new \InvalidArgumentException('Substitution pair cannot contain the same character twice');
            }
            
            // Check if characters are already used in other pairs
            if (isset($usedChars[$char1]) || isset($usedChars[$char2])) {
                throw new \InvalidArgumentException('Character cannot be used in multiple substitution pairs');
            }
            
            $usedChars[$char1] = true;
            $usedChars[$char2] = true;
        }
    }
}
