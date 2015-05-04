<?php

namespace chrisjenkinson\StructuredDocumentParser\Token;

class TokenStream implements \Countable
{
    private $tokens = [];

    public function add(TokenInterface $token)
    {
        $this->tokens[] = $token;
    }

    public function count()
    {
        return count($this->tokens);
    }

    /**
     * @param int $distance
     * @return null|TokenInterface
     */
    public function lookAhead($distance = 1)
    {
        if (!array_key_exists($distance, $this->tokens)) {
            return null;
        }

        return $this->tokens[$distance];
    }

    /**
     * @return TokenInterface
     */
    public function consumeToken()
    {
        if (!$this->getCurrentToken()) {
            throw new \RuntimeException('End of token stream');
        }

        return array_shift($this->tokens);
    }

    /**
     * @return null|TokenInterface
     */
    public function getCurrentToken()
    {
        if (0 === count($this->tokens)) {
            return null;
        }

        return $this->tokens[0];
    }

    /**
     * @param $expectedType
     * @return bool
     */
    public function expectTokenType($expectedType)
    {
        if (0 === count($this->tokens)) {
            throw new \RuntimeException(sprintf('No more tokens; expected %s', $expectedType));
        }

        $currentToken = $this->getCurrentToken();

        if ($expectedType !== $currentToken->getType()) {
            throw new \RuntimeException(
                sprintf('Token type is %s; expected %s', $currentToken->getType(), $expectedType)
            );
        }

        return true;
    }

    /**
     * @param array $expectedTypes
     * @return bool
     */
    public function expectTokenTypes(array $expectedTypes)
    {
        if (0 === count($this->tokens)) {
            throw new \RuntimeException(sprintf('No more tokens; expected any of %s', implode(', ', $expectedTypes)));
        }

        $currentToken = $this->getCurrentToken();

        foreach ($expectedTypes as $expectedType) {
            if ($expectedType === $currentToken->getType()) {
                return true;
            }
        }

        throw new \RuntimeException(
            sprintf('Token type is %s; expected any of %s', $currentToken->getType(), implode(', ', $expectedTypes))
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode("\n", $this->tokens);
    }
}
