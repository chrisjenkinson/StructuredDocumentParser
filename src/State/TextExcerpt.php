<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\State;

/**
 * @internal
 */
final class TextExcerpt
{
    private const MAX_LENGTH = 100;

    /**
     * Quotes the text up to its first newline, capped at MAX_LENGTH characters.
     */
    public static function of(string $text): string
    {
        if (str_starts_with($text, "\n")) {
            return '"\n"';
        }

        $line = explode("\n", $text, 2)[0];

        if (self::MAX_LENGTH < mb_strlen($line)) {
            return sprintf('"%s…"', mb_substr($line, 0, self::MAX_LENGTH));
        }

        return sprintf('"%s"', $line);
    }
}
