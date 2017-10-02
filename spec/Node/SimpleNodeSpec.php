<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Node;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use PhpSpec\ObjectBehavior;
use RuntimeException;

class SimpleNodeSpec extends ObjectBehavior
{
    public function it_has_a_name(): void
    {
        $this->getName()->shouldReturn('SimpleNode');
    }

    public function it_has_attributes(): void
    {
        $this->setAttribute('something', 'result');

        $this->getAttribute('something')->shouldReturn('result');
        $this->getAttributes()->shouldReturn(['something' => 'result']);
    }

    public function it_throws_exception_if_no_such_attribute_exists(): void
    {
        $this->shouldThrow(RuntimeException::class)->duringGetAttribute('nonexistent');
    }

    public function it_has_nodes(NodeInterface $node): void
    {
        $node->getName()->willReturn('ChildNode');

        $this->addNode($node);

        $this->getNode('ChildNode')->shouldReturn($node);
        $this->getNodes()->shouldReturn(['ChildNode' => $node]);
    }

    public function it_throws_exception_if_no_such_child_exists(): void
    {
        $this->shouldThrow(RuntimeException::class)->duringGetNode('nonexistent');
    }

    public function it_exports_a_tree_as_a_string(NodeInterface $node): void
    {
        $node->getName()->willReturn('ChildNode');
        $node->__toString()->willReturn('');

        $this->setAttribute('something', 'result');
        $this->addNode($node);

        $this->__toString()->shouldReturn('{
    "attributes": {
        "something": "result"
    },
    "nodes": {
        "ChildNode": {}
    }
}');
    }
}
