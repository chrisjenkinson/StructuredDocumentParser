<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Parser;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;

/**
 * Interface ParserInterface
 * @package chrisjenkinson\StructuredDocumentParser\Parser
 */
interface ParserInterface
{
    /**
     * @param TokenStream $tokens
     *
     * @return NodeInterface
     */
    public function parse(TokenStream $tokens);
}
