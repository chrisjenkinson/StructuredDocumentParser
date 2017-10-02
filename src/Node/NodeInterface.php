<?php

namespace chrisjenkinson\StructuredDocumentParser\Node;

/**
 * Interface NodeInterface
 * @package chrisjenkinson\StructuredDocumentParser\Node
 */
interface NodeInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function __toString();

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key);

    /**
     * @return mixed
     */
    public function getAttributes();

    /**
     * @param string $key
     * @param        $value
     *
     * @return void
     */
    public function setAttribute($key, $value);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasNode($key);

    /**
     * @param string $key
     *
     * @return NodeInterface
     */
    public function getNode($key);

    /**
     * @return NodeInterface[]
     */
    public function getNodes();

    /**
     * @param NodeInterface $node
     *
     * @return void
     */
    public function addNode(NodeInterface $node);

    public function removeNode(NodeInterface $node);
}
