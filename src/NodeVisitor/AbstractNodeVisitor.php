<?php

namespace chrisjenkinson\StructuredDocumentParser\NodeVisitor;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;

/**
 * Class AbstractNodeVisitor
 *
 * @package chrisjenkinson\StructuredDocumentParser\NodeVisitor
 */
class AbstractNodeVisitor implements NodeVisitorInterface
{
    /**
     * @param NodeInterface $node
     *
     * @return null
     */
    public function beforeTraverse(NodeInterface $node)
    {
        return null;
    }

    /**
     * @param NodeInterface $node
     *
     * @return null
     */
    public function afterTraverse(NodeInterface $node)
    {
        return null;
    }

    /**
     * @param NodeInterface $node
     *
     * @return null
     */
    public function enterNode(NodeInterface $node)
    {
        return null;
    }

    /**
     * @param NodeInterface $node
     *
     * @return null
     */
    public function leaveNode(NodeInterface $node)
    {
        return null;
    }
}
