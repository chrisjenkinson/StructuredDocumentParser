<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\NodeVisitor;

use PhpSpec\ObjectBehavior;
use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;

class SimpleNodeVisitorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\NodeVisitor\SimpleNodeVisitor');
    }

    public function it_does_nothing_before_traverse(NodeInterface $node)
    {
        $this->beforeTraverse($node)->shouldReturn(null);
    }

    public function it_does_nothing_after_traverse(NodeInterface $node)
    {
        $this->afterTraverse($node)->shouldReturn(null);
    }

    public function it_does_nothing_when_entering_a_node(NodeInterface $node)
    {
        $this->enterNode($node)->shouldReturn(null);
    }

    public function it_does_nothing_when_leaving_a_node(NodeInterface $node)
    {
        $this->leaveNode($node)->shouldReturn(null);
    }
}
