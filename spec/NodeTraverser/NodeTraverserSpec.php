<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\NodeTraverser;

use PhpSpec\ObjectBehavior;
use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\NodeVisitorInterface;
use chrisjenkinson\StructuredDocumentParser\NodeTraverser\NodeTraverser;

class NodeTraverserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\NodeTraverser\NodeTraverser');
    }

    public function it_can_traverse_a_node(NodeInterface $node, NodeVisitorInterface $nodeVisitor)
    {
        $node->getNodes()->willReturn([]);
        $node->getAttributes()->willReturn([]);

        $this->addVisitor($nodeVisitor);

        $this->traverse($node)->shouldReturn($node);

        $nodeVisitor->beforeTraverse($node)->shouldHaveBeenCalled();
        $nodeVisitor->enterNode($node)->shouldHaveBeenCalled();
        $nodeVisitor->leaveNode($node)->shouldHaveBeenCalled();
        $nodeVisitor->afterTraverse($node)->shouldHaveBeenCalled();
    }

    public function it_can_add_a_child(NodeInterface $node, NodeInterface $child, NodeVisitorInterface $nodeVisitor)
    {
        $node->getNodes()->willReturn([$child]);
        $node->getAttributes()->willReturn([]);

        $child->getNodes()->willReturn([]);
        $child->getAttributes()->willReturn([]);

        $node->addNode($child)->shouldBeCalled();

        $this->addVisitor($nodeVisitor);

        $this->traverse($node)->shouldReturn($node);

        $nodeVisitor->beforeTraverse($node)->shouldHaveBeenCalled();
        $nodeVisitor->afterTraverse($node)->shouldHaveBeenCalled();
    }

    public function it_can_replace_a_node(NodeInterface $node, NodeInterface $replacement, NodeVisitorInterface $nodeVisitor)
    {
        $node->getNodes()->willReturn([]);
        $node->getAttributes()->willReturn([]);

        $replacement->getNodes()->willReturn([]);
        $replacement->getAttributes()->willReturn([]);

        $nodeVisitor->beforeTraverse($node)->shouldBeCalled();
        $nodeVisitor->enterNode($node)->shouldBeCalled();
        $nodeVisitor->afterTraverse($replacement)->shouldBeCalled();

        $nodeVisitor->leaveNode($node)->willReturn($replacement);

        $this->addVisitor($nodeVisitor);

        $this->traverse($node)->shouldReturn($replacement);
    }

    public function it_can_remove_a_child(NodeInterface $node, NodeInterface $child, NodeVisitorInterface $nodeVisitor)
    {
        $node->getNodes()->willReturn([]);
        $node->getAttributes()->willReturn(['children' => [$child]]);

        $child->getNodes()->willReturn([]);
        $child->getAttributes()->willReturn([]);

        $nodeVisitor->enterNode($child)->shouldBeCalled();
        $nodeVisitor->leaveNode($child)->willReturn(NodeTraverser::REMOVE_NODE);

        $nodeVisitor->beforeTraverse($node)->shouldBeCalled();
        $nodeVisitor->enterNode($node)->shouldBeCalled();
        $nodeVisitor->leaveNode($node)->shouldBeCalled();
        $nodeVisitor->afterTraverse($node)->shouldBeCalled();

        $node->setAttribute('children', [])->shouldBeCalled();

        $this->addVisitor($nodeVisitor);

        $this->traverse($node)->shouldReturn($node);
    }

    public function it_ignores_array_attributes_when_traversing_children(NodeInterface $node, NodeVisitorInterface $nodeVisitor)
    {
        $node->getNodes()->willReturn([]);
        $node->getAttributes()->willReturn(['attr' => ['something' => true]]);

        $this->addVisitor($nodeVisitor);

        $node->setAttribute('attr', ['something' => true])->shouldBeCalled();

        $this->traverse($node)->shouldReturn($node);
    }
}
