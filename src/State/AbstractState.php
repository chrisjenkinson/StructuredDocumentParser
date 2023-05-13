<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Lexer\Cursor;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatcherInterface;
use chrisjenkinson\StructuredDocumentParser\Token\Token;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;
use ReflectionClass;

abstract class AbstractState implements StateInterface
{
    /**
        @var array<array{'matcher': MatcherInterface, 'callback': ?callable}>
    **/
    private $matchers = [];

    public function registerMatcher(MatcherInterface $matcher, callable $callback = null): void
    {
        $this->matchers[] = ['matcher' => $matcher, 'callback' => $callback];
    }

    public function findMatchingToken(Lexer $lexer, Cursor $cursor): TokenInterface
    {
        $text = $cursor->getRemainingText();

        list($matchedText, $calledMatchers, $callbacks) = $this->runMatchers($text);

        $this->guardAgainstWrongNumberOfMatches($matchedText, $text, $calledMatchers, $cursor->getCurrentPosition());

        $matcher = $calledMatchers[0];
        /** @var MatchedText $matchedText */
        $matchedText = $matchedText[0];
        $callback    = $callbacks[0];

        if (is_callable($callback)) {
            $callback($lexer);
        }

        return new Token(mb_substr($matcher, 0, -7), $matchedText->getAll());
    }

    public function guardAgainstWrongNumberOfMatches(array $matchedText, string $remainingText, array $calledMatchers, int $currentPosition): void
    {
        if (1 < count($matchedText)) {
            throw new AmbiguousTokenFoundException($this->getName(), $remainingText, $calledMatchers, $matchedText);
        }

        if (1 > count($matchedText)) {
            throw new NoTokenFoundException($this->getName(), $currentPosition, $remainingText);
        }
    }

    public function runMatchers(string $text): array
    {
        $matchedTokens  = [];
        $calledMatchers = [];
        $callbacks      = [];

        array_map(function (array $matcherAndCallback) use ($text, &$matchedTokens, &$calledMatchers, &$callbacks): void {
            $matcher  = $matcherAndCallback['matcher'];
            $callback = $matcherAndCallback['callback'];

            if ($matches = $matcher->match($text)) {
                $matchedTokens[]  = $matches;
                $calledMatchers[] = $matcher->getName();
                $callbacks[]      = $callback;
            }
        }, $this->matchers);

        return [$matchedTokens, $calledMatchers, $callbacks];
    }

    public function getName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }
}
