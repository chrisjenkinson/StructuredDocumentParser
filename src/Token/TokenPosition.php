<?php

declare(strict_types=1);

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

    public function __construct(int $line, int $column)
    {
        $this->line   = $line;
        $this->column = $column;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getColumn(): int
    {
        return $this->column;
    }
}
