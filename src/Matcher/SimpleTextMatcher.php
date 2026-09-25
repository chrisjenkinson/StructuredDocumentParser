<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

use chrisjenkinson\StructuredDocumentParser\Finder\RegexFinder;

class SimpleTextMatcher extends AbstractMatcher
{
    private readonly RegexFinder $finder;

    public function __construct()
    {
        $this->finder = new RegexFinder('/(?<all>.+)/As');
    }

    public function match(string $text): ?MatchedText
    {
        if ($this->finder->find($text)) {
            return new MatchedText($this->finder->getMatches(['all']));
        }

        return null;
    }
}
