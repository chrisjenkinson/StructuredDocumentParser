<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\NodeVisitor;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;

interface NodeVisitorInterface
{
    public function beforeTraverse(NodeInterface $node): ?NodeInterface;

    public function afterTraverse(NodeInterface $node): ?NodeInterface;

    public function enterNode(NodeInterface $node): ?NodeInterface;

    /**
     * @param NodeInterface $node
     *
     * @return NodeInterface|bool|null
     */
    public function leaveNode(NodeInterface $node);
}
