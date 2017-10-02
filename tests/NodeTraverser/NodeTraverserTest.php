<?php

namespace Test\NodeTraverser;

use chrisjenkinson\StructuredDocumentParser\Node\AbstractNode;
use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\NodeTraverser\NodeTraverser;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\AbstractNodeVisitor;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class NodeTraverserTest extends TestCase
{
    public function testItReplacesANode()
    {
        $traverser = new NodeTraverser();

        $nodeVisitor = new TestNodeVisitor();

        $node = new OriginalNode();

        $child = new ChildNode();

        $node->addNode($child);

        $traverser->addVisitor($nodeVisitor);

        Assert::assertInstanceOf(ChildNode::class, $node->getNode('ChildNode'));

        $node = $traverser->traverse($node);

        Assert::assertFalse($node->hasNode('ChildNode'));
        Assert::assertInstanceOf(NodeFromVisitor::class, $node->getNode('NodeFromVisitor'));
    }
}

class TestNodeVisitor extends AbstractNodeVisitor
{
    public function enterNode(NodeInterface $node)
    {
        if (!$node instanceof ChildNode) {
            return null;
        }

        return new NodeFromVisitor();
    }
}

class NodeFromVisitor extends AbstractNode
{
}

class OriginalNode extends AbstractNode
{
}

class ChildNode extends AbstractNode
{
}