<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\NodeVisitor;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use PhpSpec\ObjectBehavior;

class SimpleNodeVisitorSpec extends ObjectBehavior
{
    public function it_does_nothing_before_traverse(NodeInterface $node): void
    {
        $this->beforeTraverse($node)->shouldReturn(null);
    }

    public function it_does_nothing_after_traverse(NodeInterface $node): void
    {
        $this->afterTraverse($node)->shouldReturn(null);
    }

    public function it_does_nothing_when_entering_a_node(NodeInterface $node): void
    {
        $this->enterNode($node)->shouldReturn(null);
    }

    public function it_does_nothing_when_leaving_a_node(NodeInterface $node): void
    {
        $this->leaveNode($node)->shouldReturn(null);
    }
}
