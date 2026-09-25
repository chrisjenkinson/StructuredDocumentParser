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
 *         assert($visitor instanceof MarkdownVisitor);
 *
 *         return $visitor->visitHeading($this);
 *     }
 */
interface VisitableInterface
{
    public function accept(VisitorInterface $visitor): mixed;
}
