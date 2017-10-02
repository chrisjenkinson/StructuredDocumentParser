<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Node;

use ReflectionClass;
use RuntimeException;

abstract class AbstractNode implements NodeInterface
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var NodeInterface[]
     */
    protected $nodes = [];

    public function __toString(): string
    {
        return json_encode(['attributes' => $this->attributes, 'nodes' => $this->nodes], JSON_PRETTY_PRINT);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute(string $key)
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

        throw new RuntimeException(sprintf('No such node "%s"', $key));
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

    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }
}
