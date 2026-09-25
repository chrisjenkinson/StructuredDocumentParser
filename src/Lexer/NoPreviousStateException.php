<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Lexer;

use LogicException;

class NoPreviousStateException extends LogicException
{
    public function __construct()
    {
        parent::__construct('The lexer has no previous state');
    }
}
