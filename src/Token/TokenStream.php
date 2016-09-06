<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Token;

use Countable;
use RuntimeException;

/**
 * Class TokenStream
 * @package chrisjenkinson\StructuredDocumentParser\Token
 */
class TokenStream implements Countable
{
    /**
     * @var TokenInterface[]
     */
    private $tokens = [];

    /**
     * @param TokenInterface $token
     */
    public function add(TokenInterface $token)
    {
        $this->tokens[] = $token;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->tokens);
    }

    /**
     * @param int $distance
     *
     * @return null|TokenInterface
     */
    public function lookAhead(int $distance = 1)
    {
        if (!array_key_exists($distance, $this->tokens)) {
            return null;
        }

        return $this->tokens[$distance];
    }

    /**
     * @return TokenInterface
     */
    public function consumeToken(): TokenInterface
    {
        if (!$this->getCurrentToken()) {
            throw new RuntimeException('End of token stream');
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
     * @param string $expectedType
     *
     * @return true
     */
    public function expectTokenType(string $expectedType): bool
    {
        if (0 === count($this->tokens)) {
            throw new RuntimeException(sprintf('No more tokens; expected %s', $expectedType));
        }

        $currentToken = $this->getCurrentToken();

        if ($expectedType !== $currentToken->getType()) {
            throw new RuntimeException(
                sprintf('Token type is %s; expected %s', $currentToken->getType(), $expectedType)
            );
        }

        return true;
    }

    /**
     * @param string[] $expectedTypes
     *
     * @return true
     */
    public function expectTokenTypes(array $expectedTypes): bool
    {
        if (0 === count($this->tokens)) {
            throw new RuntimeException(sprintf('No more tokens; expected any of %s', implode(', ', $expectedTypes)));
        }

        $currentToken = $this->getCurrentToken();

        $expected = array_filter($expectedTypes, function (string $expectedType) use ($currentToken) {
            return ($expectedType === $currentToken->getType());
        });

        if (count($expected) >= 1) {
            return true;
        }

        throw new RuntimeException(
            sprintf('Token type is %s; expected any of %s', $currentToken->getType(), implode(', ', $expectedTypes))
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode("\n", $this->tokens);
    }
}
