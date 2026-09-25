<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Visitor;

/**
 * Implemented by nodes that dispatch to a visitor method for their own type.
 *
 * PHP does not allow narrowing the parameter type, so check for your own visitor
 * interface before dispatching:
 *
 *     public function accept(VisitorInterface $visitor): mixed
 *     {
 *         if (!$visitor instanceof MarkdownVisitor) {
 *             throw new InvalidArgumentException('Heading can only be visited by a MarkdownVisitor');
 *         }
 *
 *         return $visitor->visitHeading($this);
 *     }
 */
interface VisitableInterface
{
    public function accept(VisitorInterface $visitor): mixed;
}
