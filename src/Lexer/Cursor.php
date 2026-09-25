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

        $this->currentPosition += $length;
        $this->byteOffset += strlen($consumed);
    }

    public function getCurrentPosition(): int
    {
        return $this->currentPosition;
    }
}
