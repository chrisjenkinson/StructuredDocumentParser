<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

use ReflectionClass;

abstract class AbstractMatcher implements MatcherInterface
{
    public function getName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }
}
