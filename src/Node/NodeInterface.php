<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Node;

interface NodeInterface
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
