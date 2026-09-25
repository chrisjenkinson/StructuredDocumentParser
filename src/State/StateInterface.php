<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Lexer\Cursor;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;

interface StateInterface
{
    public function findMatchingToken(Lexer $lexer, Cursor $cursor): TokenInterface;

    public function runMatchers(string $text): array;

    public function getName(): string;
}
