<?php

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

abstract class AbstractMatcher implements MatcherInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
