<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\NodeVisitor;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;

abstract class AbstractNodeVisitor implements NodeVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function beforeTraverse(NodeInterface $node): ?NodeInterface
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function afterTraverse(NodeInterface $node): ?NodeInterface
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(NodeInterface $node): ?NodeInterface
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(NodeInterface $node): ?NodeInterface
    {
        return null;
    }
}
