<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Finder;

class RegexFinder
{
    /**
     * @var mixed[]
     */
    private array $matches = [];

    public function __construct(private readonly string $pattern)
    {
    }

    public function find(string $text): bool
    {
        $result = preg_match($this->pattern, $text, $matches);

        if (false === $result) {
            throw new RegexFailedException($this->pattern, preg_last_error_msg());
        }

        if (1 === $result) {
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
