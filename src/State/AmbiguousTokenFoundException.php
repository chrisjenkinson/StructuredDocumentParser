<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
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

    /**
     * @var TokenPosition
     */
    private $position;

    public function __construct(
        string $stateName,
        string $text,
        array $calledMatchers,
        array $matchedTokens,
        TokenPosition $position,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->stateName      = $stateName;
        $this->text           = $text;
        $this->calledMatchers = $calledMatchers;
        $this->matchedTokens  = $matchedTokens;
        $this->position       = $position;

        $matches = array_map(function (string $matcherName, MatchedText $matchedText): string {
            return sprintf('%s (%s)', $matcherName, TextExcerpt::of($matchedText->getAll()['all']));
        }, $calledMatchers, $matchedTokens);

        $message = sprintf(
            'Ambiguous token found with state %s at line %d, column %d: matchers %s',
            $stateName,
            $position->getLine(),
            $position->getColumn(),
            implode(', ', $matches)
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

    public function getPosition(): TokenPosition
    {
        return $this->position;
    }
}
