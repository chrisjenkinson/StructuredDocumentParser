<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\NodeVisitor;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;

/**
 * Class AbstractNodeVisitor
 * @package chrisjenkinson\StructuredDocumentParser\NodeVisitor
 */
abstract class AbstractNodeVisitor implements NodeVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function beforeTraverse(NodeInterface $node)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function afterTraverse(NodeInterface $node)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(NodeInterface $node)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(NodeInterface $node)
    {
        return null;
    }
}
