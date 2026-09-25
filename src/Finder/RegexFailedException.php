<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Finder;

use RuntimeException;
use Throwable;

class RegexFailedException extends RuntimeException
{
    public function __construct(
        private readonly string $pattern,
        string $error,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf('Regex %s failed: %s', $pattern, $error), $code, $previous);
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }
}
