<?php

declare(strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

interface MatcherInterface
{
    /**
     * @param string $text
     *
     * @return MatchedText|null
     */
    public function match(string $text);

    /**
     * @return string
     */
    public function getName(): string;
}
