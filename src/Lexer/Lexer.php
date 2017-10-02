<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;

class Lexer
{
    /**
     * @var StateInterface
     */
    private $state;

    /**
     * @var StateInterface[]
     */
    private $previousStates = [];

    public function __construct(StateInterface $initialState)
    {
        $this->state = $initialState;
    }

    public function tokenise(string $text): TokenStream
    {
        $tokens = new TokenStream();
        $cursor = new Cursor($text);

        while (!$cursor->isEndOfText()) {
            $token = $this->getState()->findMatchingToken($this, $cursor);

            $tokens->add($token);

            $cursor->advance(mb_strlen($token->getValue('all')));
        }

        return $tokens;
    }

    public function getState(): StateInterface
    {
        return $this->state;
    }

    public function setState(StateInterface $state): void
    {
        $this->previousStates[] = $this->state;
        $this->state            = $state;
    }

    public function getLastState(): StateInterface
    {
        return end($this->previousStates);
    }
}
