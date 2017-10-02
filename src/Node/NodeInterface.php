<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Node;

interface NodeInterface
{
    public function __toString(): string;

    public function getName(): string;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute(string $key);

    /**
     * @return mixed
     */
    public function getAttributes();

    public function setAttribute(string $key, $value): void;

    public function hasNode(string $key): bool;

    public function getNode(string $key): NodeInterface;

    /**
     * @return NodeInterface[]
     */
    public function getNodes(): array;

    public function addNode(NodeInterface $node): void;

    public function removeNode(NodeInterface $node);
}
