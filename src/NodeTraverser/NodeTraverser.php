<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\NodeTraverser;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\NodeVisitorAction;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\NodeVisitorInterface;

class NodeTraverser
{
    public const REMOVE_NODE = NodeVisitorAction::RemoveNode;

    /**
     * @var NodeVisitorInterface[]
     */
    private array $visitors = [];

    public function addVisitor(NodeVisitorInterface $visitor): void
    {
        $this->visitors[] = $visitor;
    }

    /**
     * @return NodeInterface|null the traversed node, or null if a visitor removed it
     */
    public function traverse(NodeInterface $node): ?NodeInterface
    {
        array_map(static function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (null === $before = $nodeVisitor->beforeTraverse($node)) {
                return;
            }
            $node = $before;
        }, $this->visitors);

        $node = $this->traverseNode($node);

        if (NodeVisitorAction::RemoveNode === $node) {
            return null;
        }

        array_map(static function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (null === $after = $nodeVisitor->afterTraverse($node)) {
                return;
            }
            $node = $after;
        }, $this->visitors);

        return $node;
    }

    public function traverseNode(NodeInterface $node): NodeInterface|NodeVisitorAction
    {
        $node = $this->runEnterNodeVisitors($node);

        $children   = $node->getNodes();
        $attributes = $node->getAttributes();

        $this->runTraverseNodeOnSubNodes($node, $children);
        $this->runTraverseChildrenOnAttributes($node, $attributes);

        $node = $this->runLeaveNodeVisitors($node);

        return $node;
    }

    /**
     * @param mixed[] $children
     *
     * @return mixed[]
     */
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

            if (NodeVisitorAction::RemoveNode === $child) {
                unset($children[$key]);

                continue;
            }

            $children[$key] = $child;
        }

        return $isList ? array_values($children) : $children;
    }

    private function runEnterNodeVisitors(NodeInterface $node): NodeInterface
    {
        array_map(static function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (null === $enter = $nodeVisitor->enterNode($node)) {
                return;
            }
            $node = $enter;
        }, $this->visitors);

        return $node;
    }

    /**
     * @param NodeInterface[] $children
     */
    private function runTraverseNodeOnSubNodes(NodeInterface $node, array $children): void
    {
        array_map(function (NodeInterface $child) use (&$node): void {
            $newChild = $this->traverseNode($child);

            if (NodeVisitorAction::RemoveNode === $newChild) {
                $node->removeNode($child);

                return;
            }

            if ($newChild->getName() === $child->getName()) {
                $node->replaceNode($newChild);

                return;
            }

            $node->removeNode($child);
            $node->addNode($newChild);
        }, $children);
    }

    /**
     * @param array<string, mixed> $attributes
     */
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

    private function runLeaveNodeVisitors(NodeInterface $node): NodeInterface|NodeVisitorAction
    {
        array_map(static function (NodeVisitorInterface $nodeVisitor) use (&$node): void {
            if (NodeVisitorAction::RemoveNode === $node) {
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
