<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

interface MatcherInterface
{
    /**
     * Matches against the start of $text, returning null if it does not match.
     *
     * The returned MatchedText must have an "all" key holding the exact text consumed
     * from the start of $text, which the lexer uses to advance. Any other keys are
     * made available on the resulting token.
     */
    public function match(string $text): ?MatchedText;

    public function getName(): string;
}
