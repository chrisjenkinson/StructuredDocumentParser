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
     * @param callable         $callback
     */
    public function registerMatcher(MatcherInterface $matcher, callable $callback = null)
    {
        $this->matchers[] = [$matcher, $callback];
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

        list($matchedTokens, $calledMatchers, $callback) = $this->runMatchers($text);

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

        if (is_callable($callback)) {
            $callback($lexer);
        }

        return new Token(substr($calledMatchers[0], 0, -7), $matchedTokens[0]);
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
        $callback = null;

        foreach ($this->matchers as $matcher) {
            if ($matches = $matcher[0]->match($text)) {
                $matchedTokens[]  = $matches;
                $calledMatchers[] = $matcher[0]->getName();
                $callback = $matcher[1];
            }
        }

        return [$matchedTokens, $calledMatchers, $callback];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
