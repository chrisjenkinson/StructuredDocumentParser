<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

use chrisjenkinson\StructuredDocumentParser\Finder\RegexFinder;

class SimpleTextMatcher extends AbstractMatcher
{
    /**
     * @var RegexFinder
     */
    private $finder;

    public function __construct()
    {
        $pattern = '/(?<all>.+)/Ax';

        $this->finder = new RegexFinder($pattern);
    }

    public function match(string $text): ?MatchedText
    {
        if ($this->finder->find($text)) {
            return new MatchedText($this->finder->getMatches(['all']));
        }

        return null;
    }
}
