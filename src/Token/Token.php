<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Token;

use InvalidArgumentException;

class Token implements TokenInterface
{
    private readonly string $text;

    /**
     * @param mixed[] $value
     */
    public function __construct(
        private readonly string $type,
        private readonly array $value,
        private readonly TokenPosition $position
    ) {
        if (!isset($value['all']) || !is_string($value['all'])) {
            throw new InvalidArgumentException(sprintf('Token %s needs a string "all" value', $type));
        }

        $this->text = $value['all'];
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getType(), trim($this->getText()));
    }

    public function getText(): string
    {
        return $this->text;
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

    public function hasKey(string $key): bool
    {
        return array_key_exists($key, $this->value);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(string $key): mixed
    {
        if (!array_key_exists($key, $this->value)) {
            throw new NonexistentKeyException(sprintf('No such key %s exists', $key));
        }

        return $this->value[$key];
    }
}
