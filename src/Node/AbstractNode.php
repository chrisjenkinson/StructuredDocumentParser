<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Node;

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

        throw new \RuntimeException(sprintf('No such attribute "%s".', $key));
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasNode($key): bool
    {
        if (array_key_exists($key, $this->nodes) && $this->nodes[$key] instanceof NodeInterface) {
            return true;
        }

        return false;
    }

    /**
     * @param string $key
     *
     * @return NodeInterface
     */
    public function getNode(string $key): NodeInterface
    {
        if ($this->hasNode($key)) {
            return $this->nodes[$key];
        }

        throw new \RuntimeException(sprintf('No such node "%s', $key));
    }

    /**
     * @return NodeInterface[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param NodeInterface $node
     */
    public function addNode(NodeInterface $node)
    {
        $this->nodes[$node->getName()] = $node;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode(['attributes' => $this->attributes, 'nodes' => $this->nodes], JSON_PRETTY_PRINT);
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $key
     * @param        $value
     */
    public function setAttribute(string $key, $value)
    {
        $this->attributes[$key] = $value;
    }
}
