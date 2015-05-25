<?php

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Lexer\Cursor;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatcherInterface;
use chrisjenkinson\StructuredDocumentParser\Token\Token;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;

class AbstractState implements StateInterface
{
    /**
     * @var MatcherInterface[]
     */
    private $matchers = [];

    /**
     * @param MatcherInterface $matcher
     */
    public function registerMatcher(MatcherInterface $matcher)
    {
        $this->matchers[] = $matcher;
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

        list($matchedTokens, $calledMatchers) = $this->runMatchers($text);

        if (1 < count($matchedTokens)) {
            throw new \RuntimeException(
                sprintf(
                    'Ambiguous token found with state %s in text %s with matchers %s, matches: %s',
                    $this->getName(),
                    $text,
                    implode(', ', $calledMatchers),
                    var_export($matchedTokens, true)
                )
            );
        }

        if (1 > count($matchedTokens)) {
            throw new \RuntimeException(
                sprintf(
                    'No token found with state %s at index: %d, text: %s',
                    $this->getName(),
                    $cursor->getCurrentPosition(),
                    $cursor->getRemainingText()
                )
            );
        }

        return new Token($calledMatchers[0], $matchedTokens[0]);
    }

    /**
     * @param string $text
     *
     * @return array
     */
    public function runMatchers($text)
    {
        $matchedTokens  = [];
        $calledMatchers = [];

        foreach ($this->matchers as $matcher) {
            if ($matches = $matcher->match($text)) {
                $matchedTokens[]  = $matches;
                $calledMatchers[] = $matcher->getName();
            }
        }

        return [$matchedTokens, $calledMatchers];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
