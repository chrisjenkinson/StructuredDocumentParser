<?php

declare(strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Node;

use chrisjenkinson\StructuredDocumentParser\Visitor\VisitableInterface;

interface NodeInterface extends VisitableInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
