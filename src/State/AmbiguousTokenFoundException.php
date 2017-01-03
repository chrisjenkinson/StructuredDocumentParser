<?php

namespace chrisjenkinson\StructuredDocumentParser\State;

use RuntimeException;
use Throwable;

/**
 * Class AmbiguousTokenFoundException
 * @package chrisjenkinson\StructuredDocumentParser\State
 */
class AmbiguousTokenFoundException extends RuntimeException
{
    /**
     * @var string
     */
    private $stateName;

    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $calledMatchers;

    /**
     * @var array
     */
    private $matchedTokens;

    /**
     * AmbiguousTokenFoundException constructor.
     *
     * @param string         $stateName
     * @param string         $text
     * @param array          $calledMatchers
     * @param array          $matchedTokens
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $stateName,
        $text,
        array $calledMatchers,
        array $matchedTokens,
        $code = 0,
        Throwable $previous = null
    )
    {
        $this->stateName      = $stateName;
        $this->text           = $text;
        $this->calledMatchers = $calledMatchers;
        $this->matchedTokens  = $matchedTokens;

        $message = sprintf(
            'Ambiguous token found with state %s in text %s with matchers %s, matches: %s',
            $stateName,
            $text,
            implode(', ', $calledMatchers),
            var_export($matchedTokens, true)
        );

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getCalledMatchers()
    {
        return $this->calledMatchers;
    }

    /**
     * @return array
     */
    public function getMatchedTokens()
    {
        return $this->matchedTokens;
    }

    /**
     * @return string
     */
    public function getStateName()
    {
        return $this->stateName;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
