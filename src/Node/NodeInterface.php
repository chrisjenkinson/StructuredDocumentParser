<?php

namespace chrisjenkinson\StructuredDocumentParser\Node;

interface NodeInterface
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
