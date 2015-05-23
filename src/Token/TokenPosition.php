<?php

namespace chrisjenkinson\StructuredDocumentParser\Token;

class TokenPosition
{
    /**
     * @var int
     */
    private $line;

    /**
     * @var int
     */
    private $column;

    /**
     * @param int $line
     * @param int $column
     */
    public function __construct($line, $column)
    {
        $this->line   = $line;
        $this->column = $column;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }
}
