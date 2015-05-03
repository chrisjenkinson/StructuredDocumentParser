<?php

namespace chrisjenkinson\StructuredDocumentParser\Parser;

use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;

interface ParserInterface
{
    public function parse(TokenStream $tokens);
}
