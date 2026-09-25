<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Node;

use RuntimeException;

class NodeNotFoundException extends RuntimeException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('No such node "%s"', $name));
    }
}
