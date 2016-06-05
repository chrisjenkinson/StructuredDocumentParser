<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Lexer\Cursor;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;

interface StateInterface
{
    /**
     * @param Lexer  $lexer
     * @param Cursor $cursor
     *
     * @return TokenInterface|false
     */
    public function findMatchingToken(Lexer $lexer, Cursor $cursor);

    /**
     * @param string $text
     *
     * @return array
     */
    public function runMatchers(string $text): array;

    /**
     * @return string
     */
    public function getName(): string;
}
