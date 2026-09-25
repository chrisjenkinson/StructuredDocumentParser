<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

class MatchedText
{
    /**
     * @param mixed[] $matches
     */
    public function __construct(private readonly array $matches)
    {
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function get(string $key)
    {
        if (array_key_exists($key, $this->matches)) {
            return $this->matches[$key];
        }

        return null;
    }

    public function getAll(): array
    {
        return $this->matches;
    }
}
