<?php

declare (strict_types = 1);

namespace chrisjenkinson\StructuredDocumentParser\Lexer;

/**
 * Class Cursor
 * @package chrisjenkinson\StructuredDocumentParser\Lexer
 */
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
     * Cursor constructor.
     *
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text            = $text;
        $this->currentPosition = 0;
        $this->textLength      = strlen($text);
    }

    /**
     * @return string
     */
    public function getRemainingText(): string
    {
        if ($this->isEndOfText()) {
            return '';
        }

        return substr($this->text, $this->currentPosition);
    }

    /**
     * @return bool
     */
    public function isEndOfText(): bool
    {
        return $this->currentPosition >= $this->textLength;
    }

    /**
     * @param int $length
     */
    public function advance(int $length)
    {
        $this->currentPosition += $length;
    }

    /**
     * @return int
     */
    public function getCurrentPosition(): int
    {
        return $this->currentPosition;
    }
}
