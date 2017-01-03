<?php

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
    public function get($key)
    {
        if (array_key_exists($key, $this->matches)) {
            return $this->matches[$key];
        }

        return null;
    }

    /**
     * @return mixed[]
     */
    public function getAll()
    {
        return $this->matches;
    }
}
