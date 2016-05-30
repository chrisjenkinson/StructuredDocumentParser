<?php

declare (strict_types = 1);

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
