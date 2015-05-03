<?php

namespace chrisjenkinson\StructuredDocumentParser\Node;

use chrisjenkinson\StructuredDocumentParser\Visitor\Visitable;

interface NodeInterface extends Visitable
{
    public function getName();

    public function __toString();
}