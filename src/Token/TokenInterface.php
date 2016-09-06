<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Token;

/**
 * Interface TokenInterface
 * @package chrisjenkinson\StructuredDocumentParser\Token
 */
interface TokenInterface
{
    /**
     * @return mixed[]
     */
    public function getValues(): array;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getValue(string $key);

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return TokenPosition
     */
    public function getPosition(): TokenPosition;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool;

    /**
     * @return string
     */
    public function __toString(): string;
}
