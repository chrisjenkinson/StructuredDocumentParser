<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Visitor;

/**
 * Marker interface for visitors of your own node types.
 *
 * Extend it with a visit method per node type, e.g. visitHeading(Heading $node),
 * and implement VisitableInterface on those nodes to dispatch to it.
 */
interface VisitorInterface
{
}
