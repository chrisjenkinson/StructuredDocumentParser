<?php

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
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        throw new \RuntimeException(sprintf('No such attribute "%s".', $key));
    }

    /**
     * @param $key
     * @return NodeInterface
     */
    public function getNode($key)
    {
        if (array_key_exists($key, $this->nodes)) {
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

    public function addNode(NodeInterface $node)
    {
        $this->nodes[$node->getName()] = $node;
    }

    public function getName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = sprintf(
            "%s (%s\n",
            get_class($this),
            var_export($this->getAttributes(), true)
        );

        foreach ($this->nodes as $name => $node) {
            $string .= sprintf("%s (%s\n)\n", $name, $node);
        }

        $string .= ")";

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
