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

    public function traverse(NodeInterface $node): bool|NodeInterface|null
    {
        array_map(function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (null === $before = $nodeVisitor->beforeTraverse($node)) {
                return;
            }
            $node = $before;
        }, $this->visitors);

        $node = $this->traverseNode($node);

        if (self::REMOVE_NODE === $node) {
            return $node;
        }

        array_map(function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (null === $after = $nodeVisitor->afterTraverse($node)) {
                return;
            }
            $node = $after;
        }, $this->visitors);

        return $node;
    }

    public function traverseNode(NodeInterface $node): bool|NodeInterface
    {
        $node = $this->runEnterNodeVisitors($node);

        $children   = $node->getNodes();
        $attributes = $node->getAttributes();

        $this->runTraverseNodeOnSubNodes($node, $children);
        $this->runTraverseChildrenOnAttributes($node, $attributes);

        $node = $this->runLeaveNodeVisitors($node);

        return $node;
    }

    public function traverseChildren(array $children): array
    {
        $isList = array_is_list($children);

        foreach ($children as $key => $child) {
            if (is_array($child)) {
                $children[$key] = $this->traverseChildren($child);

                continue;
            }

            if (!$child instanceof NodeInterface) {
                continue;
            }

            $child = $this->traverseNode($child);

            if (self::REMOVE_NODE === $child) {
                unset($children[$key]);

                continue;
            }

            $children[$key] = $child;
        }

        return $isList ? array_values($children) : $children;
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

            if (self::REMOVE_NODE === $newChild) {
                $node->removeNode($child);

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

            $traversed = $this->traverseChildren($attribute);

            if ($traversed === $attribute) {
                return;
            }

            $node->setAttribute($key, $traversed);
        });
    }

    private function runLeaveNodeVisitors(NodeInterface $node): NodeInterface|bool
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
