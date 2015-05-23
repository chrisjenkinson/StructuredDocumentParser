<?php

namespace chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\Matcher\MatcherInterface;
use chrisjenkinson\StructuredDocumentParser\Token\Token;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;

class Lexer
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
     * @param string $text
     * @return TokenStream
     */
    public function tokenise($text)
    {
        $tokens = new TokenStream;
        $cursor = new Cursor($text);

        while (!$cursor->isEndOfText()) {
            $token = $this->findMatchingToken($cursor->getRemainingText());

            if (!$token) {
                throw new \RuntimeException(
                    sprintf(
                        'No token found at index: %d, text: %s',
                        $cursor->getCurrentPosition(),
                        $cursor->getRemainingText()
                    )
                );
            }

            $tokens->add($token);

            $cursor->advance(strlen($token->getValue('all')));
        }

        return $tokens;
    }

    /**
     * @param string $text
     * @return Token|false
     */
    public function findMatchingToken($text)
    {
        list($matchedTokens, $calledMatchers) = $this->runMatchers($text);

        if (1 < count($matchedTokens)) {
            throw new \RuntimeException(
                sprintf(
                    'Ambiguous token found in text %s with matchers %s, matches: %s',
                    $text,
                    implode(', ', $calledMatchers),
                    var_export($matchedTokens, true)
                )
            );
        }

        if (1 === count($matchedTokens)) {
            return new Token($calledMatchers[0], $matchedTokens[0]);
        }

        return false;
    }

    /**
     * @param $text
     * @return array
     */
    public function runMatchers($text)
    {
        $matchedTokens  = [];
        $calledMatchers = [];

        foreach ($this->matchers as $matcher) {
            if ($matches = $matcher->match($text)) {
                $matchedTokens[]  = $matches;
                $calledMatchers[] = $this->getMatcherName($matcher);
            }
        }

        return [$matchedTokens, $calledMatchers];
    }

    /**
     * @param MatcherInterface $matcher
     * @return string
     */
    public function getMatcherName(MatcherInterface $matcher)
    {
        return substr((new \ReflectionClass($matcher))->getShortName(), 0, -7);
    }
}
