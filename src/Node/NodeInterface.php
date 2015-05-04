<?php

namespace chrisjenkinson\StructuredDocumentParser\Node;

use chrisjenkinson\StructuredDocumentParser\Visitor\VisitableInterface;

interface NodeInterface extends VisitableInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function __toString();
}
