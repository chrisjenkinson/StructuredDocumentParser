<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Token;

interface TokenInterface
{
    public function __toString(): string;

    /**
     * @return mixed[]
     */
    public function getValues(): array;

    public function getValue(string $key): mixed;

    public function getType(): string;

    public function getPosition(): TokenPosition;

    public function hasKey(string $key): bool;
}
