<?php

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

/**
 * Interface MatcherInterface
 * @package chrisjenkinson\StructuredDocumentParser\Matcher
 */
interface MatcherInterface
{
    /**
     * @param string $text
     *
     * @return MatchedText|null
     */
    public function match($text);

    /**
     * @return string
     */
    public function getName();
}
