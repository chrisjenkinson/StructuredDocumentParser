<?php

declare(strict_types = 1);

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

    /*
     * Lexer constructor.
     *
     * @param StateInterface $initialState
     */
    public function __construct(StateInterface $initialState)
    {
        $this->state = $initialState;
    }

    /**
     * @param string $text
     *
     * @return TokenStream
     */
    public function tokenise(string $text): TokenStream
    {
        $tokens = new TokenStream;
        $cursor = new Cursor($text);

        while (!$cursor->isEndOfText()) {
            $token = $this->getState()->findMatchingToken($this, $cursor);

            $tokens->add($token);

            $cursor->advance(strlen($token->getValue('all')));
        }

        return $tokens;
    }

    /**
     * @return StateInterface
     */
    public function getState(): StateInterface
    {
        return $this->state;
    }

    /**
     * @param StateInterface $state
     */
    public function setState(StateInterface $state)
    {
        $this->previousStates[] = $this->state;
        $this->state = $state;
    }

    /**
     * @return StateInterface
     */
    public function getLastState()
    {
        return end($this->previousStates);
    }
}
