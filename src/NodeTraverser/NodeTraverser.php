<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\NodeTraverser;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\NodeVisitorInterface;

class NodeTraverser
{
    public const REMOVE_NODE = false;

    /**
     * @var NodeVisitorInterface[]
     */
    private $visitors = [];

    public function addVisitor(NodeVisitorInterface $visitor): void
    {
        $this->visitors[] = $visitor;
    }

    /**
     * @param NodeInterface $node
     *
     * @return bool|NodeInterface|null
     */
    public function traverse(NodeInterface $node)
    {
        array_map(function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (null === $before = $nodeVisitor->beforeTraverse($node)) {
                return;
            }
            $node = $before;
        }, $this->visitors);

        $node = $this->traverseNode($node);

        array_map(function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (null === $after = $nodeVisitor->afterTraverse($node)) {
                return;
            }
            $node = $after;
        }, $this->visitors);

        return $node;
    }

    /**
     * @param NodeInterface $node
     *
     * @return bool|NodeInterface|null
     */
    public function traverseNode(NodeInterface $node)
    {
        $children   = $node->getNodes();
        $attributes = $node->getAttributes();

        $node = $this->runEnterNodeVisitors($node);

        $this->runTraverseNodeOnSubNodes($node, $children);
        $this->runTraverseChildrenOnAttributes($node, $attributes);

        $node = $this->runLeaveNodeVisitors($node);

        return $node;
    }

    public function traverseChildren(array $children): array
    {
        $keysToRemove = [];

        array_walk($children, function ($child, $key) use (&$keysToRemove, &$children): void {
            if (!$child instanceof NodeInterface) {
                return;
            }

            $child = $this->traverseNode($child);

            if (self::REMOVE_NODE === $child) {
                $keysToRemove[$key] = $key;
            }

            if (null !== $child) {
                $children[$key] = $child;
            }
        });

        return array_diff_key($children, $keysToRemove);
    }

    private function runEnterNodeVisitors(NodeInterface $node): NodeInterface
    {
        array_map(function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (null === $enter = $nodeVisitor->enterNode($node)) {
                return;
            }
            $node = $enter;
        }, $this->visitors);

        return $node;
    }

    /**
     * @param NodeInterface   $node
     * @param NodeInterface[] $children
     */
    private function runTraverseNodeOnSubNodes(NodeInterface $node, array $children): void
    {
        array_map(function (NodeInterface $child) use (&$node): void {
            $newChild = $this->traverseNode($child);

            if (null === $child) {
                return;
            }

            if (get_class($newChild) !== get_class($child)) {
                $node->removeNode($child);
            }

            $node->addNode($newChild);
        }, $children);
    }

    private function runTraverseChildrenOnAttributes(NodeInterface $node, array $attributes): void
    {
        array_walk($attributes, function ($attribute, $key) use (&$node): void {
            if (!is_array($attribute)) {
                return;
            }

            $attribute = $this->traverseChildren($attribute);
            $attribute = array_merge($attribute);

            $node->setAttribute($key, $attribute);
        });
    }

    /**
     * @param NodeInterface $node
     *
     * @return NodeInterface|bool
     */
    private function runLeaveNodeVisitors(NodeInterface $node)
    {
        array_map(function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (self::REMOVE_NODE === $node) {
                return;
            }

            $leave = $nodeVisitor->leaveNode($node);

            if (null === $leave) {
                return;
            }

            $node = $leave;
        }, $this->visitors);

        return $node;
    }
}
