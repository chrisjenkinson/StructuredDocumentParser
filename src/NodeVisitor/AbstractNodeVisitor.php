<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\NodeVisitor;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;

abstract class AbstractNodeVisitor implements NodeVisitorInterface
{
    public function beforeTraverse(NodeInterface $node): ?NodeInterface
    {
        return null;
    }

    public function afterTraverse(NodeInterface $node): ?NodeInterface
    {
        return null;
    }

    public function enterNode(NodeInterface $node): ?NodeInterface
    {
        return null;
    }

    public function leaveNode(NodeInterface $node): NodeInterface|NodeVisitorAction|null
    {
        return null;
    }
}
