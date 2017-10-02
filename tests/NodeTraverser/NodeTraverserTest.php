<?php

declare(strict_types=1);

namespace Test\NodeTraverser;

use chrisjenkinson\StructuredDocumentParser\Node\AbstractNode;
use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\NodeTraverser\NodeTraverser;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\AbstractNodeVisitor;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class NodeTraverserTest extends TestCase
{
    public function testItReplacesANode(): void
    {
        $traverser   = new NodeTraverser();
        $nodeVisitor = new TestNodeVisitor();
        $node        = new OriginalNode();
        $child       = new ChildNode();

        $node->addNode($child);

        $traverser->addVisitor($nodeVisitor);

        Assert::assertInstanceOf(ChildNode::class, $node->getNode('ChildNode'));

        $node = $traverser->traverse($node);

        Assert::assertFalse($node->hasNode('ChildNode'));
        Assert::assertInstanceOf(NodeFromVisitor::class, $node->getNode('NodeFromVisitor'));
    }

    public function testItCanChangeGrandchildrenNodes(): void
    {
        $traverser   = new NodeTraverser();
        $nodeVisitor = new Test2NodeVisitor();
        $node        = new OriginalNode();
        $child       = new ChildNode();
        $grandchild  = new GrandchildNode();

        $grandchild->setAttribute('testAttribute', 'original');

        $node->addNode($child);
        $child->addNode($grandchild);

        $traverser->addVisitor($nodeVisitor);

        Assert::assertEquals('original', $node->getNode('ChildNode')->getNode('GrandchildNode')->getAttribute('testAttribute'));

        $node = $traverser->traverse($node);

        Assert::assertEquals('replacement', $node->getNode('ChildNode')->getNode('GrandchildNode')->getAttribute('testAttribute'));
    }
}

class TestNodeVisitor extends AbstractNodeVisitor
{
    public function enterNode(NodeInterface $node): ?NodeInterface
    {
        if (!$node instanceof ChildNode) {
            return null;
        }

        return new NodeFromVisitor();
    }
}

class Test2NodeVisitor extends AbstractNodeVisitor
{
    public function enterNode(NodeInterface $node): ?NodeInterface
    {
        if (!$node instanceof ChildNode) {
            return null;
        }

        $childNode      = new ChildNode();
        $grandchildNode = new GrandchildNode();

        $childNode->addNode($grandchildNode);
        $grandchildNode->setAttribute('testAttribute', 'replacement');

        return $childNode;
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

class GrandchildNode extends AbstractNode
{
}
