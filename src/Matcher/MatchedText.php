<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

/**
 * Class MatchedText
 * @package chrisjenkinson\StructuredDocumentParser\Matcher
 */
class MatchedText
{
    /**
     * @var mixed[]
     */
    private $matches;

    /**
     * MatchedText constructor.
     *
     * @param array $matches
     */
    public function __construct(array $matches)
    {
        $this->matches = $matches;
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

    /**
     * @return mixed[]
     */
    public function getAll(): array
    {
        return $this->matches;
    }
}
