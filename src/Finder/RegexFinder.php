<?php

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

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public function find($text)
    {
        if (preg_match($this->pattern, $text, $matches)) {
            $this->matches = $matches;

            return true;
        }

        return false;
    }

    public function getMatches($array)
    {
        return array_intersect_key($this->matches, array_flip($array));
    }
}
