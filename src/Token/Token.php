<?php

namespace chrisjenkinson\StructuredDocumentParser\Token;

use RuntimeException;

/**
 * Class Token
 * @package chrisjenkinson\StructuredDocumentParser\Token
 */
class Token implements TokenInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed[]
     */
    private $value = [];

    /**
     * @var TokenPosition
     */
    private $position;

    /**
     * Token constructor.
     *
     * @param string $type
     * @param mixed  $value
     */
    public function __construct($type, $value)
    {
        $this->type  = $type;
        $this->value = $value;
    }

    /**
     * @return mixed[]
     */
    public function getValues()
    {
        return $this->value;
    }

    /**
     * @return TokenPosition
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param TokenPosition $position
     */
    public function setPosition(TokenPosition $position)
    {
        $this->position = $position;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s (%s)', $this->getType(), trim($this->getValue('all')));
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getValue($key)
    {
        if (!array_key_exists($key, $this->value)) {
            throw new RuntimeException(sprintf('No such key %s exists', $key));
        }

        return $this->value[$key];
    }
}
