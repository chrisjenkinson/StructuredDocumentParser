<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Node;

use ReflectionClass;
use RuntimeException;

abstract class AbstractNode implements NodeInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * @var NodeInterface[]
     */
    protected array $nodes = [];

    public function __toString(): string
    {
        return json_encode($this, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
    }

    /**
     * @return array{attributes: array<string, mixed>, nodes: NodeInterface[]}
     */
    public function jsonSerialize(): array
    {
        return ['attributes' => $this->attributes, 'nodes' => $this->nodes];
    }

    public function getAttribute(string $key): mixed
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        throw new RuntimeException(sprintf('No such attribute "%s".', $key));
    }

    public function hasNode(string $key): bool
    {
        return array_key_exists($key, $this->nodes) && $this->nodes[$key] instanceof NodeInterface;
    }

    public function getNode(string $key): NodeInterface
    {
        if ($this->hasNode($key)) {
            return $this->nodes[$key];
        }

        throw new NodeNotFoundException($key);
    }

    /**
     * @return NodeInterface[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function addNode(NodeInterface $node): void
    {
        if ($this->hasNode($node->getName())) {
            throw new DuplicateNodeException($node->getName());
        }

        $this->nodes[$node->getName()] = $node;
    }

    public function replaceNode(NodeInterface $node): void
    {
        if (!$this->hasNode($node->getName())) {
            throw new NodeNotFoundException($node->getName());
        }

        $this->nodes[$node->getName()] = $node;
    }

    public function removeNode(NodeInterface $node): void
    {
        unset($this->nodes[$node->getName()]);
    }

    public function getName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }
}
