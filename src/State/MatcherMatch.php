<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use Closure;

/**
 * @internal
 */
final class MatcherMatch
{
    public function __construct(
        public readonly string $matcherName,
        public readonly MatchedText $matchedText,
        public readonly ?Closure $callback
    ) {
    }
}
