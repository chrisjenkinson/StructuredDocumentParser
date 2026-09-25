<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Lexer\Cursor;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatcherInterface;
use chrisjenkinson\StructuredDocumentParser\Token\Token;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use Closure;
use ReflectionClass;

abstract class AbstractState implements StateInterface
{
    /**
        @var array<array{'matcher': MatcherInterface, 'callback': ?callable}>
    **/
    private $matchers = [];

    public function registerMatcher(MatcherInterface $matcher, ?callable $callback = null): void
    {
        $this->matchers[] = ['matcher' => $matcher, 'callback' => $callback];
    }

    public function findMatchingToken(Lexer $lexer, Cursor $cursor): TokenInterface
    {
        $text     = $cursor->getRemainingText();
        $position = new TokenPosition($cursor->getLine(), $cursor->getColumn());

        $match = $this->findSingleMatch($this->runMatchers($text), $text, $cursor->getCurrentPosition(), $position);

        if (null !== $match->callback) {
            ($match->callback)($lexer);
        }

        return new Token(
            $this->getTokenType($match->matcherName),
            $match->matchedText->getAll(),
            $position
        );
    }

    private function getTokenType(string $matcherName): string
    {
        if (str_ends_with($matcherName, 'Matcher')) {
            return mb_substr($matcherName, 0, -mb_strlen('Matcher'));
        }

        return $matcherName;
    }

    private function guardAgainstInvalidMatchedText(string $matcherName, MatchedText $matchedText, string $text): void
    {
        $matches = $matchedText->getAll();

        if (!array_key_exists('all', $matches)) {
            throw new InvalidMatchedTextException($matcherName, 'it has no "all" key');
        }

        if (!is_string($matches['all'])) {
            throw new InvalidMatchedTextException($matcherName, 'its "all" value is not a string');
        }

        if (!str_starts_with($text, $matches['all'])) {
            throw new InvalidMatchedTextException($matcherName, 'its "all" value is not at the start of the text');
        }
    }

    /**
     * @param MatcherMatch[] $matches
     */
    private function findSingleMatch(array $matches, string $remainingText, int $currentPosition, TokenPosition $position): MatcherMatch
    {
        if (1 < count($matches)) {
            throw new AmbiguousTokenFoundException(
                $this->getName(),
                $remainingText,
                array_map(fn (MatcherMatch $match): string => $match->matcherName, $matches),
                array_map(fn (MatcherMatch $match): MatchedText => $match->matchedText, $matches),
                $position
            );
        }

        if (1 > count($matches)) {
            throw new NoTokenFoundException($this->getName(), $currentPosition, $remainingText, $position);
        }

        return $matches[0];
    }

    /**
     * @return MatcherMatch[]
     */
    private function runMatchers(string $text): array
    {
        $matches = [];

        foreach ($this->matchers as ['matcher' => $matcher, 'callback' => $callback]) {
            $matchedText = $matcher->match($text);

            if (null === $matchedText) {
                continue;
            }

            $this->guardAgainstInvalidMatchedText($matcher->getName(), $matchedText, $text);

            $matches[] = new MatcherMatch(
                $matcher->getName(),
                $matchedText,
                null === $callback ? null : Closure::fromCallable($callback)
            );
        }

        return $matches;
    }

    public function getName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }
}
