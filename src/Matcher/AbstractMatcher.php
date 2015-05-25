<?php

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

class AbstractMatcher
{
    /**
     * @return string
     */
    public function getName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
