<?php

namespace chrisjenkinson\StructuredDocumentParser\Token;

class Token implements TokenInterface
{
    private $type;

    private $value = [];

    /**
     * @var TokenPosition
     */
    private $position;

    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function getValues()
    {
        return $this->value;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition(TokenPosition $position)
    {
        $this->position = $position;
    }

    public function hasKey($index)
    {
        return array_key_exists($index, $this->value);
    }

    public function __toString()
    {
        return sprintf("%s (%s)", $this->getType(), trim($this->getValue('all')));
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue($index)
    {
        if (!array_key_exists($index, $this->value)) {
            throw new \RuntimeException(sprintf('No such index %s exists', $index));
        }

        return $this->value[$index];
    }
}
