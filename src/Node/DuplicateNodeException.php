<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Node;

use LogicException;

class DuplicateNodeException extends LogicException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('A node named "%s" already exists', $name));
    }
}
