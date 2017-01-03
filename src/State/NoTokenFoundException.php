<?php

namespace chrisjenkinson\StructuredDocumentParser\State;

use RuntimeException;
use Throwable;

/**
 * Class NoTokenFoundException
 * @package chrisjenkinson\StructuredDocumentParser\State
 */
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
     * NoTokenFoundException constructor.
     *
     * @param string         $stateName
     * @param int            $currentPosition
     * @param string         $remainingText
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($stateName, $currentPosition, $remainingText, $code = 0, Throwable $previous = null)
    {
        $this->stateName       = $stateName;
        $this->currentPosition = $currentPosition;
        $this->remainingText   = $remainingText;

        $message = sprintf(
            'No token found with state %s, current position: %d, remaining text: %s',
            $stateName,
            $currentPosition,
            $remainingText
        );

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getStateName()
    {
        return $this->stateName;
    }

    /**
     * @return int
     */
    public function getCurrentPosition()
    {
        return $this->currentPosition;
    }

    /**
     * @return string
     */
    public function getRemainingText()
    {
        return $this->remainingText;
    }
}
