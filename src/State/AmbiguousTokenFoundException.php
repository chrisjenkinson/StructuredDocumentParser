<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\State;

class AmbiguousTokenFoundException extends \RuntimeException
{
    /**
     * AmbiguousTokenFoundException constructor.
     *
     * @param string          $stateName
     * @param string          $text
     * @param                 $calledMatchers
     * @param                 $matchedTokens
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $stateName,
        string $text,
        $calledMatchers,
        $matchedTokens,
        int $code = 0,
        \Throwable $previous = null
    )
    {
        $message = sprintf(
            'Ambiguous token found with state %s in text %s with matchers %s, matches: %s',
            $stateName,
            $text,
            implode(', ', $calledMatchers),
            var_export($matchedTokens, true)
        );

        parent::__construct($message, $code, $previous);
    }
}
