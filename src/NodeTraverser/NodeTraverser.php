<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\NodeTraverser;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\NodeVisitorInterface;

/**
 * Class NodeTraverser
 *
 * @package chrisjenkinson\StructuredDocumentParser\NodeTraverser
 */
class NodeTraverser
{
    const REMOVE_NODE = false;

    /**
     * @var NodeVisitorInterface[]
     */
    private $visitors = [];

    /**
     * @param NodeVisitorInterface $visitor
     */
    public function addVisitor(NodeVisitorInterface $visitor)
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
        foreach ($this->visitors as $visitor) {
            if (null !== $before = $visitor->beforeTraverse($node)) {
                $node = $before;
            }
        }

        $node = $this->traverseNode($node);

        foreach ($this->visitors as $visitor) {
            if (null !== $after = $visitor->afterTraverse($node)) {
                $node = $after;
            }
        }

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

        foreach ($this->visitors as $visitor) {
            if (null !== $enter = $visitor->enterNode($node)) {
                $node = $enter;
            }
        }

        foreach ($children as $child) {
            $child = $this->traverseNode($child);

            if (null !== $child) {
                $node->addNode($child);
            }
        }

        foreach ($attributes as $attributeKey => $attribute) {
            if (is_array($attribute)) {
                $attributes[$attributeKey] = $this->traverseChildren($attribute);

                $attributes[$attributeKey] = array_merge($attributes[$attributeKey]);

                $node->setAttribute($attributeKey, $attributes[$attributeKey]);
            }
        }

        foreach ($this->visitors as $visitor) {
            $leave = $visitor->leaveNode($node);

            if (self::REMOVE_NODE === $leave) {
                return false;
            }

            if (null !== $leave) {
                $node = $leave;
            }
        }

        return $node;
    }

    /**
     * @param array $children
     *
     * @return array
     */
    public function traverseChildren(array $children): array
    {
        foreach ($children as $key => $child) {
            if (!$child instanceof NodeInterface) {
                continue;
            }

            $child = $this->traverseNode($child);

            if (null !== $child) {
                $children[$key] = $child;
            }

            if (self::REMOVE_NODE === $child) {
                unset($children[$key]);
            }
        }

        return $children;
    }
}
