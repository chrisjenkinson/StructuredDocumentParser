<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Token;

class Token implements TokenInterface
{
    /**
     * @param mixed[] $value
     */
    public function __construct(
        private readonly string $type,
        private readonly array $value,
        private readonly TokenPosition $position
    ) {
    }

    public function __toString(): string
    {
        $all = $this->getValue('all');
        assert(is_string($all));

        return sprintf('%s (%s)', $this->getType(), trim($all));
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
