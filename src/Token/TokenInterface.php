<?php

namespace chrisjenkinson\StructuredDocumentParser\Token;

interface TokenInterface
{
    /**
     * @return mixed
     */
    public function getValues();

    /**
     * @param $index
     * @return mixed
     */
    public function getValue($index);

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @return TokenPosition
     */
    public function getPosition();

    /**
     * @param $index
     * @return mixed
     */
    public function hasKey($index);

    /**
     * @return string
     */
    public function __toString();
}