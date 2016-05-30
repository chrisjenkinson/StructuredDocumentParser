<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\State;

class NoTokenFoundException extends \RuntimeException
{
    /**
     * NoTokenFoundException constructor.
     *
     * @param string          $stateName
     * @param int             $currentPosition
     * @param string          $remainingText
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $stateName,
        int $currentPosition,
        string $remainingText,
        int $code = 0,
        \Throwable $previous = null
    ) {
        $message = sprintf('No token found with state %s, current position: %d, remaining text: %s', $stateName,
            $currentPosition, $remainingText);

        parent::__construct($message, $code, $previous);
    }
}
