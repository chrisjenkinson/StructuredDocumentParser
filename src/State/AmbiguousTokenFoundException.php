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
     * @param string[]      $calledMatchers
     * @param MatchedText[] $matchedTokens
     */
    public function __construct(
        private readonly string $stateName,
        private readonly string $text,
        private readonly array $calledMatchers,
        private readonly array $matchedTokens,
        private readonly TokenPosition $position,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $matches = array_map(static function (string $matcherName, MatchedText $matchedText): string {
            $all = $matchedText->getAll()['all'] ?? null;

            return sprintf('%s (%s)', $matcherName, TextExcerpt::of(is_string($all) ? $all : ''));
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

    /**
     * @return string[]
     */
    public function getCalledMatchers(): array
    {
        return $this->calledMatchers;
    }

    /**
     * @return MatchedText[]
     */
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
