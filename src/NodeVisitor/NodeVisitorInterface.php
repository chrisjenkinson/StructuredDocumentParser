<?php

namespace chrisjenkinson\StructuredDocumentParser\NodeVisitor;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;

/**
 * Interface NodeVisitorInterface
 * @package chrisjenkinson\StructuredDocumentParser\NodeVisitor
 */
interface NodeVisitorInterface
{
    /**
     * @param NodeInterface $node
     *
     * @return NodeInterface|null
     */
    public function beforeTraverse(NodeInterface $node);

    /**
     * @param NodeInterface $node
     *
     * @return NodeInterface|null
     */
    public function afterTraverse(NodeInterface $node);

    /**
     * @param NodeInterface $node
     *
     * @return NodeInterface|null
     */
    public function enterNode(NodeInterface $node);

    /**
     * @param NodeInterface $node
     *
     * @return NodeInterface|null
     */
    public function leaveNode(NodeInterface $node);
}
