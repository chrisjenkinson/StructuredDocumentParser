<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Node;

use chrisjenkinson\StructuredDocumentParser\Node\DuplicateNodeException;
use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\Node\NodeNotFoundException;
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
        $this->shouldThrow(NodeNotFoundException::class)->duringGetNode('nonexistent');
    }

    public function it_throws_when_adding_a_node_whose_name_is_already_taken(NodeInterface $node, NodeInterface $other): void
    {
        $node->getName()->willReturn('ChildNode');
        $other->getName()->willReturn('ChildNode');

        $this->addNode($node);

        $this->shouldThrow(DuplicateNodeException::class)->duringAddNode($other);
        $this->getNode('ChildNode')->shouldReturn($node);
    }

    public function it_replaces_a_node_with_the_same_name(NodeInterface $node, NodeInterface $replacement): void
    {
        $node->getName()->willReturn('ChildNode');
        $replacement->getName()->willReturn('ChildNode');

        $this->addNode($node);
        $this->replaceNode($replacement);

        $this->getNodes()->shouldReturn(['ChildNode' => $replacement]);
    }

    public function it_throws_when_replacing_a_node_that_does_not_exist(NodeInterface $node): void
    {
        $node->getName()->willReturn('ChildNode');

        $this->shouldThrow(NodeNotFoundException::class)->duringReplaceNode($node);
    }

    public function it_exports_a_tree_as_a_string(NodeInterface $child, NodeInterface $listItem): void
    {
        $child->getName()->willReturn('ChildNode');
        $child->jsonSerialize()->willReturn(['exported' => 'child']);
        $listItem->jsonSerialize()->willReturn(['exported' => 'list item']);

        $this->setAttribute('something', 'result');
        $this->setAttribute('items', [$listItem]);
        $this->addNode($child);

        $this->__toString()->shouldReturn('{
    "attributes": {
        "something": "result",
        "items": [
            {
                "exported": "list item"
            }
        ]
    },
    "nodes": {
        "ChildNode": {
            "exported": "child"
        }
    }
}');
    }

    public function it_replaces_invalid_utf8_when_exporting_as_a_string(): void
    {
        $this->setAttribute('bad', "a\xffb");

        $this->__toString()->shouldReturn('{
    "attributes": {
        "bad": "a\ufffdb"
    },
    "nodes": []
}');
    }
}
