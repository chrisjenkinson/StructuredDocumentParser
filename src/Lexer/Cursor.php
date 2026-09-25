<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Lexer;

class Cursor
{
    private int $currentPosition = 0;

    private readonly int $textLength;

    private int $byteOffset = 0;

    /**
     * $line and $column give the position of the start of $text, for text taken from a larger document.
     */
    public function __construct(
        private readonly string $text,
        private int $line = 1,
        private int $column = 1,
    ) {
        $this->textLength = mb_strlen($text);
    }

    public function getRemainingText(): string
    {
        if ($this->isEndOfText()) {
            return '';
        }

        return substr($this->text, $this->byteOffset);
    }

    public function isEndOfText(): bool
    {
        return $this->currentPosition >= $this->textLength;
    }

    public function advance(int $length): void
    {
        // A UTF-8 character is at most 4 bytes, so this slice always holds $length characters.
        $consumed = mb_substr(substr($this->text, $this->byteOffset, $length * 4), 0, $length);
        $newlines = mb_substr_count($consumed, "\n");

        if (0 < $newlines) {
            $this->line += $newlines;
            $this->column = mb_strlen($consumed) - mb_strrpos($consumed, "\n");
        } else {
            $this->column += mb_strlen($consumed);
        }

        $this->currentPosition += $length;
        $this->byteOffset += strlen($consumed);
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function getCurrentPosition(): int
    {
        return $this->currentPosition;
    }
}
