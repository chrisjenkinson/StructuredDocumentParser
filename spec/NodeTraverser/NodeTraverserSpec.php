<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\NodeTraverser;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\NodeTraverser\NodeTraverser;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\NodeVisitorInterface;
use PhpSpec\ObjectBehavior;

class NodeTraverserSpec extends ObjectBehavior
{
    public function it_can_traverse_a_node(NodeInterface $node, NodeVisitorInterface $nodeVisitor, NodeVisitorInterface $nodeVisitor2): void
    {
        $node->getNodes()->willReturn([]);
        $node->getAttributes()->willReturn([]);

        $this->addVisitor($nodeVisitor);
        $this->addVisitor($nodeVisitor2);

        $this->traverse($node)->shouldReturn($node);

        $nodeVisitor->beforeTraverse($node)->shouldHaveBeenCalled();
        $nodeVisitor->enterNode($node)->shouldHaveBeenCalled();
        $nodeVisitor->leaveNode($node)->shouldHaveBeenCalled();
        $nodeVisitor->afterTraverse($node)->shouldHaveBeenCalled();
    }

    public function it_can_add_a_child(NodeInterface $node, NodeInterface $child, NodeVisitorInterface $nodeVisitor, NodeVisitorInterface $nodeVisitor2): void
    {
        $node->getNodes()->willReturn([$child]);
        $node->getAttributes()->willReturn([]);

        $child->getNodes()->willReturn([]);
        $child->getAttributes()->willReturn([]);

        $node->addNode($child)->shouldBeCalled();

        $this->addVisitor($nodeVisitor);
        $this->addVisitor($nodeVisitor2);

        $this->traverse($node)->shouldReturn($node);

        $nodeVisitor->beforeTraverse($node)->shouldHaveBeenCalled();
        $nodeVisitor->afterTraverse($node)->shouldHaveBeenCalled();
    }

    public function it_can_replace_a_node(NodeInterface $root, NodeInterface $node, NodeInterface $replacement, NodeVisitorInterface $nodeVisitor, NodeVisitorInterface $nodeVisitor2): void
    {
        $root->getNodes()->willReturn([$node]);
        $root->getAttributes()->willReturn([]);
        $root->removeNode($node)->shouldBeCalled();

        $node->getNodes()->willReturn([]);
        $node->getAttributes()->willReturn([]);

        $replacement->getNodes()->willReturn([]);
        $replacement->getAttributes()->willReturn([]);

        $nodeVisitor->beforeTraverse($root)->willReturn(null);
        $nodeVisitor->enterNode($root)->willReturn(null);
        $nodeVisitor->enterNode($node)->willReturn($replacement);
        $nodeVisitor->leaveNode($replacement)->willReturn(null);
        $nodeVisitor->leaveNode($root)->willReturn(null);
        $nodeVisitor->afterTraverse($root)->willReturn(null);

        $root->addNode($replacement)->shouldBeCalled();

        $this->addVisitor($nodeVisitor);
        $this->addVisitor($nodeVisitor2);

        $this->traverse($root)->shouldReturn($root);
    }

    public function it_can_remove_a_child(NodeInterface $node, NodeInterface $child, NodeVisitorInterface $nodeVisitor, NodeVisitorInterface $nodeVisitor2): void
    {
        $node->getNodes()->willReturn([]);
        $node->getAttributes()->willReturn(['children' => [$child]]);

        $child->getNodes()->willReturn([]);
        $child->getAttributes()->willReturn([]);

        $nodeVisitor->enterNode($child)->willReturn(null);
        $nodeVisitor->leaveNode($child)->willReturn(NodeTraverser::REMOVE_NODE);

        $nodeVisitor->beforeTraverse($node)->willReturn(null);
        $nodeVisitor->enterNode($node)->willReturn(null);
        $nodeVisitor->leaveNode($node)->willReturn(null);
        $nodeVisitor->afterTraverse($node)->willReturn(null);

        $node->setAttribute('children', [])->shouldBeCalled();

        $this->addVisitor($nodeVisitor);
        $this->addVisitor($nodeVisitor2);

        $this->traverse($node)->shouldReturn($node);
    }

    public function it_ignores_array_attributes_when_traversing_children(NodeInterface $node, NodeVisitorInterface $nodeVisitor, NodeVisitorInterface $nodeVisitor2): void
    {
        $node->getNodes()->willReturn([]);
        $node->getAttributes()->willReturn(['attr' => ['something' => true]]);

        $this->addVisitor($nodeVisitor);
        $this->addVisitor($nodeVisitor2);

        $node->setAttribute('attr', ['something' => true])->shouldBeCalled();

        $this->traverse($node)->shouldReturn($node);
    }
}
