<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Node;

use JsonSerializable;

interface NodeInterface extends JsonSerializable
{
    public function __toString(): string;

    public function getName(): string;

    public function getAttribute(string $key): mixed;

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array;

    public function setAttribute(string $key, mixed $value): void;

    public function hasNode(string $key): bool;

    public function getNode(string $key): NodeInterface;

    /**
     * @return NodeInterface[]
     */
    public function getNodes(): array;

    public function addNode(NodeInterface $node): void;

    public function replaceNode(NodeInterface $node): void;

    public function removeNode(NodeInterface $node): void;
}
