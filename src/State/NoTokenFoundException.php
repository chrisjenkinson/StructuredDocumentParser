<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use RuntimeException;
use Throwable;

class NoTokenFoundException extends RuntimeException
{
    /**
     * @var string
     */
    private $stateName;

    /**
     * @var int
     */
    private $currentPosition;

    /**
     * @var string
     */
    private $remainingText;

    /**
     * @var TokenPosition
     */
    private $position;

    public function __construct(string $stateName, int $currentPosition, string $remainingText, TokenPosition $position, int $code = 0, ?Throwable $previous = null)
    {
        $this->stateName       = $stateName;
        $this->currentPosition = $currentPosition;
        $this->remainingText   = $remainingText;
        $this->position        = $position;

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
