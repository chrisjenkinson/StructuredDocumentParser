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

        $currentIndex = 0;
        $textLength = strlen($text);

        while ($currentIndex < $textLength) {
            $token = $this->findMatchingToken(substr($text, $currentIndex));

            if (!$token) {
                //return $tokens;

                throw new \RuntimeException(
                    sprintf('No token found at index: %d, text: %s', $currentIndex, substr($text, $currentIndex))
                );
            }

            $tokens->add($token);

            $currentIndex += strlen($token->getValue('all'));
        }

        return $tokens;
    }

    /**
     * @param string $text
     * @return bool|Token
     */
    public function findMatchingToken($text)
    {
        $matchedTokens = [];
        $matchers = [];

        foreach ($this->matchers as $matcher) {
            if ($matches = $matcher->match($text)) {
                $matchedTokens[] = $matches;
                $matchers[] = $matcher;
            }
        }

        if (1 < count($matchedTokens)) {
            throw new \RuntimeException(
                sprintf(
                    'Ambiguous token found in text %s with matchers %s, matches: %s',
                    $text,
                    implode(
                        ', ',
                        array_map(
                            function ($matcher) {
                                return $this->getMatcherName($matcher);
                            },
                            $matchers
                        )
                    ),
                    var_export($matchedTokens, true)
                )
            );
        }

        if (1 === count($matchedTokens)) {
            return new Token($this->getMatcherName($matchers[0]), $matchedTokens[0]);
        }

        return false;
    }

    /**
     * @param MatcherInterface $matcher
     * @return string
     */
    public
    function getMatcherName(
        MatcherInterface $matcher
    ) {
        return substr((new \ReflectionClass($matcher))->getShortName(), 0, -7);
    }
}
