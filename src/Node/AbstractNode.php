<?php

declare(strict_types = 1);

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
        if (array_key_exists($key, $this->nodes) && $this->nodes[$key] instanceof NodeInterface) {
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
        $string = sprintf(
            "%s (%s\n",
            get_class($this),
            var_export($this->getAttributes(), true)
        );

        foreach ($this->nodes as $name => $node) {
            $string .= sprintf("%s (%s\n)\n", $name, $node);
        }

        $string .= ')';

        return $string;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
