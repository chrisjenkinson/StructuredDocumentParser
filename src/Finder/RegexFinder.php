<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Finder;

class RegexFinder
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @var array
     */
    private $matches = [];

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function find(string $text): bool
    {
        if (preg_match($this->pattern, $text, $matches)) {
            $this->matches = $matches;

            return true;
        }

        return false;
    }

    public function getMatches(array $keys): array
    {
        return array_intersect_key($this->matches, array_flip($keys));
    }
}
