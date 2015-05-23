<?php

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

    public function __construct($text)
    {
        $this->text            = $text;
        $this->currentPosition = 0;
        $this->textLength      = strlen($text);
    }

    /**
     * @return string
     */
    public function getRemainingText()
    {
        if ($this->isEndOfText()) {
            return '';
        }

        return substr($this->text, $this->currentPosition);
    }

    /**
     * @return bool
     */
    public function isEndOfText()
    {
        return $this->currentPosition >= $this->textLength;
    }

    /**
     * @param int $length
     */
    public function advance($length)
    {
        $this->currentPosition += $length;
    }

    /**
     * @return int
     */
    public function getCurrentPosition()
    {
        return $this->currentPosition;
    }
}
