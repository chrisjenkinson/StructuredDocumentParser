<?php

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

use chrisjenkinson\StructuredDocumentParser\Finder\RegexFinder;

/**
 * Class SimpleTextMatcher
 * @package chrisjenkinson\StructuredDocumentParser\Matcher
 */
class SimpleTextMatcher extends AbstractMatcher
{
    /**
     * @var RegexFinder
     */
    private $finder;

    /**
     * SimpleTextMatcher constructor.
     */
    public function __construct()
    {
        $pattern = '/(?<all>.+)/Ax';

        $this->finder = new RegexFinder($pattern);
    }

    /**
     * @param string $text
     *
     * @return MatchedText|null
     */
    public function match($text)
    {
        if ($this->finder->find($text)) {
            return new MatchedText($this->finder->getMatches(['all']));
        }

        return null;
    }
}
