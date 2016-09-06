<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

/**
 * Class AbstractMatcher
 * @package chrisjenkinson\StructuredDocumentParser\Matcher
 */
abstract class AbstractMatcher implements MatcherInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
