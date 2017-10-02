<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

interface MatcherInterface
{
    public function match(string $text): ?MatchedText;

    /**
     * @return string
     */
    public function getName(): string;
}
