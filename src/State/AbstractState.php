<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Lexer\Cursor;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatcherInterface;
use chrisjenkinson\StructuredDocumentParser\Token\Token;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;

abstract class AbstractState implements StateInterface
{
    /**
     * @var MatcherInterface[]
     */
    private $matchers = [];

    /**
     * @param MatcherInterface $matcher
     * @param null|callable    $callback
     */
    public function registerMatcher(MatcherInterface $matcher, callable $callback = null)
    {
        $this->matchers[] = ['matcher' => $matcher, 'callback' => $callback];
    }

    /**
     * @param Lexer  $lexer
     * @param Cursor $cursor
     *
     * @return TokenInterface
     */
    public function findMatchingToken(Lexer $lexer, Cursor $cursor)
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

        return new Token(substr($matcher, 0, -7), $matchedText->getAll());
    }

    public function guardAgainstWrongNumberOfMatches(
        array $matchedText,
        string $remainingText,
        array $calledMatchers,
        int $currentPosition
    ) {

        if (1 < count($matchedText)) {
            throw new AmbiguousTokenFoundException($this->getName(), $remainingText, $calledMatchers, $matchedText);
        }

        if (1 > count($matchedText)) {
            throw new NoTokenFoundException($this->getName(), $currentPosition, $remainingText);
        }
    }

    /**
     * @param string $text
     *
     * @return array
     */
    public function runMatchers(string $text): array
    {
        $matchedTokens  = [];
        $calledMatchers = [];
        $callbacks      = [];

        array_map(function (array $matcherAndCallback) use ($text, &$matchedTokens, &$calledMatchers, &$callbacks) {
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
