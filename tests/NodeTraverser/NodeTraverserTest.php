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

    public function testItRemovesAChildNode(): void
    {
        $traverser = new NodeTraverser();
        $node      = new OriginalNode();

        $node->addNode(new ChildNode());

        $traverser->addVisitor(new RemoveChildNodeVisitor());

        $node = $traverser->traverse($node);

        Assert::assertFalse($node->hasNode('ChildNode'));
    }

    public function testItRemovesTheRootNodeWithoutCallingAfterTraverse(): void
    {
        $traverser = new NodeTraverser();
        $visitor   = new RecordAfterTraverseVisitor();

        $traverser->addVisitor(new RemoveChildNodeVisitor());
        $traverser->addVisitor($visitor);

        Assert::assertSame(NodeTraverser::REMOVE_NODE, $traverser->traverse(new ChildNode()));
        Assert::assertFalse($visitor->afterTraverseCalled);
    }

    public function testItPreservesKeysOfArrayAttributesWithoutNodes(): void
    {
        $traverser = new NodeTraverser();
        $node      = new OriginalNode();

        $node->setAttribute('values', [5 => 'five', 9 => 'nine']);

        $node = $traverser->traverse($node);

        Assert::assertSame([5 => 'five', 9 => 'nine'], $node->getAttribute('values'));
    }

    public function testItPreservesKeysWhenRemovingANodeFromAKeyedArrayAttribute(): void
    {
        $traverser = new NodeTraverser();
        $node      = new OriginalNode();
        $keep      = new GrandchildNode();

        $node->setAttribute('children', ['first' => new ChildNode(), 'second' => $keep]);

        $traverser->addVisitor(new RemoveChildNodeVisitor());

        $node = $traverser->traverse($node);

        Assert::assertSame(['second' => $keep], $node->getAttribute('children'));
    }

    public function testItReindexesAListAttributeWhenRemovingANode(): void
    {
        $traverser = new NodeTraverser();
        $node      = new OriginalNode();
        $keep      = new GrandchildNode();

        $node->setAttribute('children', [new ChildNode(), $keep]);

        $traverser->addVisitor(new RemoveChildNodeVisitor());

        $node = $traverser->traverse($node);

        Assert::assertSame([$keep], $node->getAttribute('children'));
    }

    public function testItVisitsNodesInNestedArrayAttributes(): void
    {
        $traverser = new NodeTraverser();
        $node      = new OriginalNode();
        $keep      = new GrandchildNode();

        $node->setAttribute('children', ['group' => [new ChildNode(), $keep]]);

        $traverser->addVisitor(new RemoveChildNodeVisitor());

        $node = $traverser->traverse($node);

        Assert::assertSame(['group' => [$keep]], $node->getAttribute('children'));
    }
}

class RecordAfterTraverseVisitor extends AbstractNodeVisitor
{
    public bool $afterTraverseCalled = false;

    public function afterTraverse(NodeInterface $node): ?NodeInterface
    {
        $this->afterTraverseCalled = true;

        return null;
    }
}

class RemoveChildNodeVisitor extends AbstractNodeVisitor
{
    public function leaveNode(NodeInterface $node)
    {
        if (!$node instanceof ChildNode) {
            return null;
        }

        return NodeTraverser::REMOVE_NODE;
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
