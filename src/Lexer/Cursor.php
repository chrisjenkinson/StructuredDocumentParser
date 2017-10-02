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

        return mb_substr($this->text, $this->currentPosition);
    }

    public function isEndOfText(): bool
    {
        return $this->currentPosition >= $this->textLength;
    }

    public function advance(int $length): void
    {
        $this->currentPosition += $length;
    }

    public function getCurrentPosition(): int
    {
        return $this->currentPosition;
    }
}
