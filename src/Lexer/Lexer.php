<?php

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

    /**
     * @param string $text
     *
     * @return TokenStream
     */
    public function tokenise($text)
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
    public function getState()
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
