<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Lexer;

use RuntimeException;
use Throwable;

class ZeroLengthTokenLoopException extends RuntimeException
{
    public function __construct(
        private readonly string $stateName,
        private readonly int $currentPosition,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'State %s was re-entered at position %d without the lexer advancing',
            $stateName,
            $currentPosition
        );

        parent::__construct($message, $code, $previous);
    }

    public function getStateName(): string
    {
        return $this->stateName;
    }

    public function getCurrentPosition(): int
    {
        return $this->currentPosition;
    }
}
