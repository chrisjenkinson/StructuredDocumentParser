<?php

declare (strict_types = 1);

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
    public function getName(): string;

    /**
     * @return string
     */
    public function __toString(): string;

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

    /**
     * @param string $key
     * @param        $value
     *
     * @return void
     */
    public function setAttribute(string $key, $value);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasNode(string $key): bool;

    /**
     * @param string $key
     *
     * @return NodeInterface
     */
    public function getNode(string $key): NodeInterface;

    /**
     * @return NodeInterface[]
     */
    public function getNodes(): array;

    /**
     * @param NodeInterface $node
     *
     * @return void
     */
    public function addNode(NodeInterface $node);
}
