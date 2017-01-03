<?php

namespace chrisjenkinson\StructuredDocumentParser\Finder;

/**
 * Class RegexFinder
 * @package chrisjenkinson\StructuredDocumentParser\Finder
 */
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

    /**
     * RegexFinder constructor.
     *
     * @param $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    public function find($text)
    {
        if (preg_match($this->pattern, $text, $matches)) {
            $this->matches = $matches;

            return true;
        }

        return false;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMatches(array $keys)
    {
        return array_intersect_key($this->matches, array_flip($keys));
    }
}
