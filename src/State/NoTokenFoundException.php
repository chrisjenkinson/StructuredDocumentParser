<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use RuntimeException;
use Throwable;

class NoTokenFoundException extends RuntimeException
{
    public function __construct(
        private readonly string $stateName,
        private readonly int $currentPosition,
        private readonly string $remainingText,
        private readonly TokenPosition $position,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'No token found with state %s at line %d, column %d: %s',
            $stateName,
            $position->getLine(),
            $position->getColumn(),
            TextExcerpt::of($remainingText)
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

    public function getRemainingText(): string
    {
        return $this->remainingText;
    }

    public function getPosition(): TokenPosition
    {
        return $this->position;
    }
}
