<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use RuntimeException;
use Throwable;

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

    public function __construct(
        string $stateName,
        string $text,
        array $calledMatchers,
        array $matchedTokens,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->stateName      = $stateName;
        $this->text           = $text;
        $this->calledMatchers = $calledMatchers;
        $this->matchedTokens  = $matchedTokens;

        $message = sprintf(
            'Ambiguous token found with state %s in text %s with matchers %s, matches: %s',
            $stateName,
            $text,
            implode(', ', $calledMatchers),
            var_export(array_map(function (MatchedText $matchedText): array {
                return $matchedText->getAll();
            }, $matchedTokens), true)
        );

        parent::__construct($message, $code, $previous);
    }

    public function getCalledMatchers(): array
    {
        return $this->calledMatchers;
    }

    public function getMatchedTokens(): array
    {
        return $this->matchedTokens;
    }

    public function getStateName(): string
    {
        return $this->stateName;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
