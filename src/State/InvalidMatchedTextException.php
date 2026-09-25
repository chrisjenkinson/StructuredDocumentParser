<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use LogicException;
use Throwable;

class InvalidMatchedTextException extends LogicException
{
    public function __construct(
        private readonly string $matcherName,
        string $reason,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf('Matcher %s returned invalid matched text: %s', $matcherName, $reason), $code, $previous);
    }

    public function getMatcherName(): string
    {
        return $this->matcherName;
    }
}
