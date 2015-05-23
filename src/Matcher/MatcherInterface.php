<?php

namespace chrisjenkinson\StructuredDocumentParser\Matcher;

interface MatcherInterface
{
    /**
     * @param $text
     * @return array|null
     */
    public function match($text);
}
