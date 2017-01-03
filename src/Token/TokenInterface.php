<?php

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
    public function getValues();

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getValue($key);

    /**
     * @return string
     */
    public function getType();

    /**
     * @return TokenPosition
     */
    public function getPosition();

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey($key);

    /**
     * @return string
     */
    public function __toString();
}
