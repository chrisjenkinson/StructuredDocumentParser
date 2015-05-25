<?php

namespace chrisjenkinson\StructuredDocumentParser\Parser;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;

interface ParserInterface
{
    /**
     * @param TokenStream $tokens
     *
     * @return NodeInterface
     */
    public function parse(TokenStream $tokens);
}
