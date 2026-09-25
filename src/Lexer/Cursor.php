<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Lexer;

class Cursor
{
    /**
     * @var int
     */
    private $currentPosition;

    /**
     * @var int
     */
    private $textLength;

    /**
     * @var string
     */
    private $text;

    /**
     * @var int
     */
    private $line = 1;

    /**
     * @var int
     */
    private $column = 1;

    /**
     * @var int
     */
    private $byteOffset = 0;

    public function __construct(string $text)
    {
        $this->text            = $text;
        $this->currentPosition = 0;
        $this->textLength      = mb_strlen($text);
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
            $this->line  += $newlines;
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
