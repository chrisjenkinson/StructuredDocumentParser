<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Token;

use RuntimeException;

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
    public function __construct(string $type, $value)
    {
        $this->type  = $type;
        $this->value = $value;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getType(), trim($this->getValue('all')));
    }

    /**
     * @return mixed[]
     */
    public function getValues(): array
    {
        return $this->value;
    }

    public function getPosition(): TokenPosition
    {
        return $this->position;
    }

    public function setPosition(TokenPosition $position): void
    {
        $this->position = $position;
    }

    public function hasKey(string $key): bool
    {
        return array_key_exists($key, $this->value);
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getValue(string $key)
    {
        if (!array_key_exists($key, $this->value)) {
            throw new RuntimeException(sprintf('No such key %s exists', $key));
        }

        return $this->value[$key];
    }
}
